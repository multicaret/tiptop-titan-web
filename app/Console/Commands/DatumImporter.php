<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchTranslation;
use App\Models\Location;
use App\Models\OldModels\OldBranch;
use App\Models\OldModels\OldBranchTranslation;
use App\Models\OldModels\OldCategory;
use App\Models\OldModels\OldCategoryTranslation;
use App\Models\OldModels\OldMedia;
use App\Models\OldModels\OldProduct;
use App\Models\OldModels\OldProductTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Taxonomy;
use App\Models\TaxonomyTranslation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection as Collection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\Console\Helper\ProgressBar;

class DatumImporter extends Command
{
    protected $signature = 'datum:importer {model? : The name of the model}';
    protected $description = 'Command to import branch';
    private string $modelName;// 'Product';
    private const DEFAULT_BRANCH_ID = 473;
    public const DEFAULT_REGION = 2;
    public const DEFAULT_CITY = 1;
    private const CREATOR_EDITOR_ID = 1;
    private const DEFAULT_CHAIN = 1;
    private const DEFAULT_RESTAURANT_ID = 269;
    private ProgressBar $bar;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->showChoice();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if ($this->modelName === 'Branch') {
            $this->insertDefaultBranch();
        } elseif ($this->modelName === 'Product') {
            $this->importProducts(500);
        } elseif ($this->modelName === 'Category') {
            Taxonomy::truncate();
            TaxonomyTranslation::truncate();
            $parentIds = [31, 441, 508, 509, 510, 511, 512, 554, 557, 597, 654, 666];
            $groceryCategories = OldCategory::whereIn('id', $parentIds)->get();
            foreach ($parentIds as $parentId) {
                $groceryCategories = $groceryCategories->merge(OldCategory::whereParentId($parentId)->get());
            }
            $this->bar = $this->output->createProgressBar($groceryCategories->count());
            $this->bar->start();
            foreach ($groceryCategories as $oldCategory) {
                $this->insertCategory($oldCategory);
                $this->bar->advance();
            }
        }
        $this->bar->finish();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->newLine(2);
        $this->info('Import is finished 🤪.');
        $this->newLine(2);
    }

    private function showChoice(): void
    {
        $this->modelName = (string) $this->argument('model');
        $this->line('Start importing....');
        if (empty($this->modelName)) {
            $this->modelName = $this->choice(
                'What is model name?',
                ['Branch', 'Product', 'Category'],
                1
            );
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

    private function insertDefaultBranch(): void
    {
        Branch::truncate();
        BranchTranslation::truncate();
        $first = OldBranch::find(self::DEFAULT_BRANCH_ID);
        $this->bar = $this->output->createProgressBar(1);
        $tempBranch = [];
        $this->bar->start();
        $this->bar->advance();
        foreach (OldBranch::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempBranch[$newModelKey] = $first->{$oldModelKey};
        }
        $tempBranch['uuid'] = $this->getUuidString();
        $tempBranch['chain_id'] = self::DEFAULT_CHAIN;
        $tempBranch['creator_id'] = self::CREATOR_EDITOR_ID;
        $tempBranch['editor_id'] = self::CREATOR_EDITOR_ID;
        $tempBranch['region_id'] = self::DEFAULT_REGION;
        $tempBranch['city_id'] = self::DEFAULT_CITY;
        $tempBranch['type'] = Branch::CHANNEL_GROCERY_OBJECT; // Todo: must update
        $tempBranch['status'] = Branch::STATUS_ACTIVE;
        $isInserted = Branch::insert($tempBranch);
        if ($isInserted) {
            $freshBranch = Branch::find(self::DEFAULT_BRANCH_ID);
            foreach ($first->translations as $translation) {
                $attributesComparing = OldBranchTranslation::attributesComparing();
                $tempTranslation = [];
                foreach ($attributesComparing as $oldAttribute => $newAttribute) {
                    $tempTranslation[$newAttribute] = $translation->{$oldAttribute};
                }
                BranchTranslation::insert($tempTranslation);
            }
            $this->storeLocation($first, $freshBranch);
            $this->addSingleImage($freshBranch, 'Dish');
        }
    }


    private function getUuidString($min = 10, $max = 99): string
    {
        return Controller::uuid().mt_rand($min, $max);
    }


    private function insertProducts(OldProduct $oldProduct)
    {
        $tempProduct = [];
        $categories = $oldProduct->categories_count > 0 ? $oldProduct->categories : collect([]);
        $mainCategory = optional($categories->first())->id;
        if (is_null($mainCategory)) {
            dd($oldProduct->toArray());
        }
        foreach (OldProduct::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempProduct[$newModelKey] = $oldProduct->{$oldModelKey};
        }
        $tempProduct['uuid'] = $this->getUuidString();
        $tempProduct['chain_id'] = self::DEFAULT_CHAIN;
        $tempProduct['branch_id'] = self::DEFAULT_BRANCH_ID;
        $tempProduct['creator_id'] = self::CREATOR_EDITOR_ID;
        $tempProduct['editor_id'] = self::CREATOR_EDITOR_ID;
        $tempProduct['category_id'] = $mainCategory;
        $tempProduct['status'] = Product::STATUS_ACTIVE;
        $tempProduct['type'] = Product::CHANNEL_GROCERY_OBJECT; // Todo: must update
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
            $this->addSingleImage($freshProduct, 'Dish');
        }
    }

    private function storeLocation($first, $freshBranch)
    {
        $location = new Location();
        $location->creator_id = $location->editor_id = 1;
        $location->contactable_id = $freshBranch->id;
        $location->contactable_type = Branch::class;
        $location->type = Location::TYPE_CONTACT;
        $location->name = $first->contact_name;
        $location->emails = $first->contact_email;
        $location->phones = $first->contact_phone_1;
        $location->save();
    }

    public function remoteFileExists($url): bool
    {
        return \Http::get($url)->successful();
    }


    private function insertCategory(OldCategory $oldCategory)
    {
        $tempCategory = [
            'uuid' => $this->getUuidString(),
            'chain_id' => self::DEFAULT_CHAIN,
            'branch_id' => self::DEFAULT_BRANCH_ID,
            'creator_id' => self::CREATOR_EDITOR_ID,
            'editor_id' => self::CREATOR_EDITOR_ID,
            'left' => 1,
            'right' => 1,
            'depth' => 1,
            'step' => 1,
            'status' => Taxonomy::STATUS_ACTIVE,
            'type' => OldCategory::typesComparing()[$oldCategory->type],
        ];
        foreach (OldCategory::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempCategory[$newModelKey] = $oldCategory->{$oldModelKey};
        }

        $isInserted = Taxonomy::insert($tempCategory);
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

    private function importProducts(int $count): void
    {
        Product::truncate();
        ProductTranslation::truncate();
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
}
