<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchTranslation;
use App\Models\Chain;
use App\Models\ChainTranslation;
use App\Models\Location;
use App\Models\OldModels\OldBranch;
use App\Models\OldModels\OldBranchTranslation;
use App\Models\OldModels\OldCategory;
use App\Models\OldModels\OldCategoryTranslation;
use App\Models\OldModels\OldChain;
use App\Models\OldModels\OldChainTranslation;
use App\Models\OldModels\OldMedia;
use App\Models\OldModels\OldProduct;
use App\Models\OldModels\OldProductTranslation;
use App\Models\OldModels\OldUser;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Taxonomy;
use App\Models\TaxonomyTranslation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as Collection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatumImporter extends Command
{
    protected $signature = 'datum:importer {model? : The name of the model}';
    protected $description = 'Command to import old data';
    private string $modelName;// 'Product';
    private const DEFAULT_BRANCH_ID = 473;
    public const DEFAULT_REGION = 2;
    public const DEFAULT_CITY = 1;
    private const CREATOR_EDITOR_ID = 1;
    private const DEFAULT_CHAIN = 1;
    private const DEFAULT_RESTAURANT_ID = 269;
    private const CHAIN_SKIPPED_IDS = [207, 269];
    public const CHOICE_GROCERY_DEFAULT_BRANCH = 'grocery-default-branch';
    public const CHOICE_GROCERY_PRODUCTS = 'grocery-products';
    public const CHOICE_FOOD_PRODUCTS = 'food-products';
    public const CHOICE_GROCERY_CATEGORIES = 'grocery-categories';
    public const CHOICE_PRODUCT_IMAGES = 'product-images';
    public const CHOICE_FOOD_CHAINS = 'food-chains';
    public const CHOICE_USERS = 'USERS';
    private ProgressBar $bar;
    private Collection $foodCategories;
    private array $importerChoices;

    public function __construct()
    {
        $this->foodCategories = collect([]);
        $this->importerChoices = self::choicesArray();
        parent::__construct();
    }

    private static function choicesArray(): array
    {
        return [
            self::CHOICE_GROCERY_DEFAULT_BRANCH,
            self::CHOICE_GROCERY_PRODUCTS,
            self::CHOICE_FOOD_PRODUCTS,
            self::CHOICE_GROCERY_CATEGORIES,
            self::CHOICE_PRODUCT_IMAGES,
            self::CHOICE_FOOD_CHAINS,
            self::CHOICE_USERS,
        ];
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->showChoice();
        if ($this->modelName === self::CHOICE_FOOD_PRODUCTS) {
            $this->foodCategories = Taxonomy::on()->pluck('id', 'id');
        }
    }

    public function handle(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if ($this->modelName === self::CHOICE_GROCERY_DEFAULT_BRANCH) {
            $this->insertGroceryDefaultBranch();
        } elseif ($this->modelName === self::CHOICE_GROCERY_PRODUCTS) {
            $this->importGroceryProducts(500);
        } elseif ($this->modelName === self::CHOICE_FOOD_PRODUCTS) {
            $this->importFoodProducts();
        } elseif ($this->modelName === self::CHOICE_FOOD_CHAINS) {
            $this->importFoodChains(500);
        } elseif ($this->modelName === self::CHOICE_GROCERY_CATEGORIES) {
            $this->importGroceryCategories();
        } elseif ($this->modelName === self::CHOICE_PRODUCT_IMAGES) {
            $this->importProductsImages(500);
        } elseif ($this->modelName === self::CHOICE_USERS) {
            $this->importUsers();
        }
        $this->bar->finish();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->newLine(2);
        $this->info('Import '.$this->modelName.' is finished 🤪.');
        $this->newLine(2);
    }

    private function showChoice(): void
    {
        $this->modelName = (string) $this->argument('model');
        $this->line('Start importing....');
        if (empty($this->modelName)) {
            $this->modelName = $this->choice('What is model name?', $this->importerChoices, 1);
        }
    }

    private function addSingleImage($newModel, string $oldModelName, $imageCollection = 'cover'): void
    {
        $oldMediaData = OldMedia::where([
            ['model_type', "Modules\\Jo3aan\\Entities\\$oldModelName"], ['model_id', $newModel->id]
        ])->first();
        if ( ! is_null($oldMediaData)) {
            $s3Url = $oldMediaData->getProductS3Url($oldMediaData->id, $oldMediaData->file_name);
            $errorMessage = null;
            try {
                $uuid = self::getUuidString(25);
                $newModel->addMediaFromUrl($s3Url)
                         ->setFileName("{$newModel->id}-{$uuid}.{$oldMediaData->getExtensionAttribute()}")
                         ->toMediaCollection($imageCollection);
            } catch (FileDoesNotExist $e) {
                $errorMessage = 'Failed to load image because file does not exist: '.$e->getMessage();
            } catch (FileIsTooBig $e) {
                $errorMessage = 'Failed to load image because file is too big: '.$e->getMessage();
            } catch (FileCannotBeAdded $e) {
                $errorMessage = 'Failed to load image because file cannot be added: '.$e->getMessage();
            }
            if ( ! is_null($errorMessage)) {
//                $this->line($errorMessage);
                \Log::info($errorMessage);
            }
        }
    }

    private function insertBranch(OldBranch $oldBranch, $type): void
    {
        $tempBranch = [];
        foreach ($oldBranch->attributesComparing($type) as $oldModelKey => $newModelKey) {
            if ( ! is_null($oldBranch->{$oldModelKey})) {
                $tempBranch[$newModelKey] = $oldBranch->{$oldModelKey};
            }
        }
        $tempBranch['uuid'] = $this->getUuidString();
        $tempBranch['chain_id'] = ! is_null($oldBranch->chain_id) ? $oldBranch->chain_id : self::DEFAULT_CHAIN;
        $tempBranch['creator_id'] = self::CREATOR_EDITOR_ID;
        $tempBranch['editor_id'] = self::CREATOR_EDITOR_ID;
        $tempBranch['region_id'] = self::DEFAULT_REGION;
        $tempBranch['city_id'] = self::DEFAULT_CITY;
        $tempBranch['type'] = $type;
        $tempBranch['status'] = OldBranch::statusesComparing()[$oldBranch->status];
        $tempBranch['has_tip_top_delivery'] = $type === Branch::CHANNEL_GROCERY_OBJECT;
        $isInserted = Branch::insert($tempBranch);
        if ($isInserted) {
            $freshBranch = Branch::find($oldBranch->id);
            if ($type === Branch::CHANNEL_FOOD_OBJECT) {
                $groceryCategoriesIds = $oldBranch->categories->pluck('id');
                $freshBranch->foodCategories()->sync($groceryCategoriesIds);
            }
            foreach ($oldBranch->translations as $translation) {
                $attributesComparing = OldBranchTranslation::attributesComparing();
                $tempTranslation = [];
                foreach ($attributesComparing as $oldAttribute => $newAttribute) {
                    $tempTranslation[$newAttribute] = $translation->{$oldAttribute};
                }
                BranchTranslation::insert($tempTranslation);
            }
            $this->storeLocation($oldBranch, $freshBranch);
        }
    }


    private function getUuidString($min = 10, $max = 99): string
    {
        return Controller::uuid().mt_rand($min, $max);
    }


    private function insertProducts(OldProduct $oldProduct, $type = Product::CHANNEL_GROCERY_OBJECT)
    {
        $tempProduct = [];
        if (isset($oldProduct->chain_id)) {
            $branchId = ! empty($oldProduct->branch_id) ? $oldProduct->branch_id : null;
        } else {
            $branchId = self::DEFAULT_BRANCH_ID;
        }
        $categories = collect([]);
        if ($oldProduct->categories->count() > 0) {
            $categories = $oldProduct->categories;
        }
        $mainCategory = optional($categories->first())->id;
        if (is_null($mainCategory)) {
            dd($oldProduct->toArray());
        }
        foreach (OldProduct::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempProduct[$newModelKey] = $oldProduct->{$oldModelKey};
        }
        $tempProduct['uuid'] = $this->getUuidString();
        $tempProduct['chain_id'] = ! is_null($oldProduct->chain_id) ? $oldProduct->chain_id : self::DEFAULT_CHAIN;
        $tempProduct['branch_id'] = $branchId;
        $tempProduct['creator_id'] = self::CREATOR_EDITOR_ID;
        $tempProduct['editor_id'] = self::CREATOR_EDITOR_ID;
        $tempProduct['category_id'] = $mainCategory;
        $tempProduct['status'] = Product::STATUS_ACTIVE;
        $tempProduct['type'] = $type;
        $tempProduct['available_quantity'] = 100;
        $tempProduct['is_storage_tracking_enabled'] = 0;
        $tempProduct['price_discount_by_percentage'] = $oldProduct->discount_type === OldProduct::TYPE_DISCOUNT_PERCENTAGE;
        $isInserted = Product::insert($tempProduct);
        if ($isInserted) {
            $freshProduct = Product::find($oldProduct->id);
            $groceryCategoriesIds = $categories->pluck('id');
            $freshProduct->categories()->sync($groceryCategoriesIds);
            foreach ($oldProduct->translations as $translation) {
                $attributesComparing = OldProductTranslation::attributesComparing();
                $tempTranslation = [];
                foreach ($attributesComparing as $oldAttribute => $newAttribute) {
                    $tempTranslation[$newAttribute] = $translation->{$oldAttribute};
                }
                ProductTranslation::insert($tempTranslation);
            }
//            $this->addSingleImage($freshProduct, 'Dish');
        }
    }


    private function insertProductImage(int $oldProductId)
    {
        $freshProduct = Product::find($oldProductId);
        $this->addSingleImage($freshProduct, 'Dish');
    }

    private function storeLocation($oldBranch, $freshBranch)
    {
        $location = new Location();
        $location->creator_id = $location->editor_id = 1;
        $location->contactable_id = $freshBranch->id;
        $location->contactable_type = Branch::class;
        $location->type = Location::TYPE_CONTACT;
        $location->name = $oldBranch->contact_name;
        $location->emails = $oldBranch->contact_email;
        $location->phones = $oldBranch->contact_phone_1;
        $location->save();
    }

    public function remoteFileExists($url): bool
    {
        return \Http::get($url)->successful();
    }


    private function insertCategory(OldCategory $oldCategory, $type)
    {
        if (isset($oldCategory->branch_id)) {
            $branchId = empty($oldCategory->branch_id) ? null : $oldCategory->branch_id;
        } else {
            $branchId = self::DEFAULT_BRANCH_ID;
        }
        $tempCategory = [
            'uuid' => $this->getUuidString(),
            'chain_id' => ! is_null($oldCategory->chain_id) ? $oldCategory->chain_id : self::DEFAULT_CHAIN,
            'branch_id' => $branchId,
            'creator_id' => self::CREATOR_EDITOR_ID,
            'editor_id' => self::CREATOR_EDITOR_ID,
            'left' => 1,
            'right' => 1,
            'depth' => 1,
            'step' => 1,
            'status' => Taxonomy::STATUS_ACTIVE,
            'type' => $type,
        ];
        foreach (OldCategory::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempCategory[$newModelKey] = $oldCategory->{$oldModelKey};
        }

        try {
            $isInserted = Taxonomy::insert($tempCategory);
        } catch (\Exception $e) {
            $this->newLine(2);
            dd($e->getMessage(), $tempCategory, $this->foodCategories->toArray());
            $this->newLine(2);
        }
        if ($isInserted) {
            $freshCategory = Taxonomy::find($oldCategory->id);
            foreach ($oldCategory->translations as $translation) {
                $attributesComparing = OldCategoryTranslation::attributesComparing();
                $tempTranslation = [];
                foreach ($attributesComparing as $oldAttribute => $newAttribute) {
                    $tempTranslation[$newAttribute] = $translation->{$oldAttribute};
                }
                TaxonomyTranslation::insert($tempTranslation);
            }
//            $this->addSingleImage($freshCategory, 'Dish');
        }
    }

    private function importGroceryProducts(int $count): void
    {
//        Product::truncate();
//        ProductTranslation::truncate();
        $oldProducts = OldProduct::orderBy('created_at')
                                 ->withCount('categories')
                                 ->where('restaurant_id', self::DEFAULT_RESTAURANT_ID)
                                 ->take($count)->get();
        $this->bar = $this->output->createProgressBar($oldProducts->count());
        $this->bar->start();
        foreach ($oldProducts as $oldProduct) {
            $this->insertProducts($oldProduct);
            $this->bar->advance();
        }
    }

    private function importProductsImages(int $count): void
    {
        $oldProductsIds = OldProduct::orderBy('created_at')
                                    ->withCount('categories')
                                    ->where('restaurant_id', self::DEFAULT_RESTAURANT_ID)
                                    ->take($count)->pluck('id');
        $this->bar = $this->output->createProgressBar($oldProductsIds->count());
        $this->bar->start();
        foreach ($oldProductsIds as $oldProductId) {
            $this->insertProductImage($oldProductId);
            $this->bar->advance();
        }
    }

    private function importGroceryCategories(): void
    {
//        Taxonomy::truncate();
//        TaxonomyTranslation::truncate();
        $parentIds = [31, 441, 508, 509, 510, 511, 512, 554, 557, 597, 654, 666];
        $groceryCategories = OldCategory::whereIn('id', $parentIds)->get();
        foreach ($parentIds as $parentId) {
            $groceryCategories = $groceryCategories->merge(OldCategory::whereParentId($parentId)->get());
        }
        $this->bar = $this->output->createProgressBar($groceryCategories->count());
        $this->bar->start();
        foreach ($groceryCategories as $oldCategory) {
            $this->insertCategory($oldCategory, Taxonomy::TYPE_GROCERY_CATEGORY);
            $this->bar->advance();
        }
    }

    private function importFoodChains(int $count)
    {
        $oldChains = OldChain::with('branches', 'branches.categories')
                             ->whereNotIn('id', self::CHAIN_SKIPPED_IDS)
                             ->take($count)
                             ->get();
        $this->bar = $this->output->createProgressBar($oldChains->count());
        $this->bar->start();
        foreach ($oldChains as $oldChain) {
            foreach ($oldChain->branches->pluck('categories') as $branchIndex => $categories) {
                foreach ($categories as $category) {
                    if (is_null($category->parent_id) && ! $this->foodCategories->has($category->id)) {
                        $this->foodCategories = $this->foodCategories->put($category->id, $category->id);
                        $category->chain_id = $oldChain->id;
                        $category->branch_id = optional($oldChain->branches[$branchIndex])->id;
                        \Log::info('$category->branch_id is: '.json_encode($category->branch_id));
                        $this->insertCategory($category, Taxonomy::TYPE_MENU_CATEGORY);
                    }
                }
            }
        }
        foreach ($oldChains as $oldChain) {
            $this->insertFoodChain($oldChain);
            $this->bar->advance();
        }
    }

    private function insertFoodChain(OldChain $oldChain): void
    {
        $tempChain = [];
        foreach (OldChain::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempChain[$newModelKey] = $oldChain->{$oldModelKey};
        }
        $tempChain['uuid'] = $this->getUuidString();
        $tempChain['creator_id'] = self::CREATOR_EDITOR_ID;
        $tempChain['editor_id'] = self::CREATOR_EDITOR_ID;
        $tempChain['region_id'] = config('defaults.region.id');
        $tempChain['city_id'] = config('defaults.city.id');
        $tempChain['currency_id'] = config('defaults.currency.id');
        $tempChain['type'] = Chain::CHANNEL_FOOD_OBJECT;
        $tempChain['status'] = OldChain::statusesComparing()[$oldChain->status];
        $isInserted = Chain::insert($tempChain);

        if ($isInserted) {
            $freshChain = Chain::find($oldChain->id);
            foreach ($oldChain->translations as $translation) {
                $attributesComparing = OldChainTranslation::attributesComparing();
                $tempTranslation = [];
                foreach ($attributesComparing as $oldAttribute => $newAttribute) {
                    $tempTranslation[$newAttribute] = $translation->{$oldAttribute};
                }
                ChainTranslation::insert($tempTranslation);
            }
            foreach ($oldChain->branches as $oldBranch) {
                $oldBranch->chain_id = $oldChain->id;
                $this->insertBranch($oldBranch, Branch::CHANNEL_FOOD_OBJECT);
            }
        }
    }

    public function insertGroceryDefaultBranch(): void
    {
//        Branch::truncate();
//        BranchTranslation::truncate();
        $this->bar = $this->output->createProgressBar(1);
        $this->bar->start();
        $oldBranch = OldBranch::find(self::DEFAULT_BRANCH_ID);
        $this->insertBranch($oldBranch, Branch::CHANNEL_GROCERY_OBJECT);
        $this->bar->advance();
    }

    private function importFoodProducts()
    {

        $oldChains = OldChain::whereNotIn('id', self::CHAIN_SKIPPED_IDS)
                             ->get();
        $this->bar = $this->output->createProgressBar($oldChains->count());
        $this->line('Start import Food Categories');
        $this->newLine();
        $this->bar->start();
        $this->newLine();
        foreach ($oldChains as $oldChain) {
            $oldChain->load(['products.categories']);
            foreach ($oldChain->products->pluck('categories') as $productIndex => $categories) {
                foreach ($categories as $category) {
                    if ( ! $this->foodCategories->has($category->id)) {
                        $this->foodCategories = $this->foodCategories->put($category->id, $category->id);
                        $category->chain_id = $oldChain->id;
                        $category->branch_id = '';
                        $this->insertCategory($category, Taxonomy::TYPE_FOOD_CATEGORY);
                    }
                }
            }
        }
        $this->bar->finish();


        $this->line('End import Food Categories');

        $this->line('Start import Food Products');

        foreach ($oldChains as $oldChain) {
            $oldChain->load(['products', 'products.categories']);
            $this->newLine();
            $this->newLine();
            $this->bar = $this->output->createProgressBar($oldChain->products->count());
            $this->bar->start();
            foreach ($oldChain->products as $oldProduct) {
                $oldProduct->chain_id = $oldChain->id;
                $oldProduct->branch_id = '';
                $this->insertProducts($oldProduct, Product::CHANNEL_FOOD_OBJECT);
                $this->bar->advance();
            }
            $this->bar->finish();
        }
    }

    private function importUsers()
    {
        $oldUsers = OldUser::all();
        $this->newLine();
        $this->bar = $this->output->createProgressBar($oldUsers->count());
        $this->bar->start();
        foreach ($oldUsers as $oldUser) {
            if ($oldUser->email) {
                $this->insertUser($oldUser);
            }
            $this->bar->advance();
        }
    }

    private function insertUser(OldUser $oldUser)
    {
        $tempUser = [];
        foreach ($oldUser->attributesComparing() as $oldModelKey => $newModelKey) {
            $tempUser[$newModelKey] = $oldUser->{$oldModelKey};
        }
        $tempUser['settings'] = json_encode([]);
        $tempUser['status'] = $oldUser->status === OldUser::STATUS_ACTIVE ? User::STATUS_ACTIVE : User::STATUS_INACTIVE;
        try {
            $isInserted = User::insert($tempUser);
        } catch (\Exception $e) {
            dd($e->getMessage(), PHP_EOL, $tempUser);
        }

    }

}
