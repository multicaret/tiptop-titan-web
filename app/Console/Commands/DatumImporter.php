<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchTranslation;
use App\Models\Location;
use App\Models\OldModels\OldBranch;
use App\Models\OldModels\OldBranchTranslation;
use App\Models\OldModels\OldMedia;
use App\Models\OldModels\OldProduct;
use App\Models\OldModels\OldProductTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Taxonomy;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection as Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class DatumImporter extends Command
{
    protected $signature = 'datum:importer
                                {model? : The name of the model}';
    protected $description = 'Command to import branch';
    private Carbon $beginsAt;
    private string $modelName;// 'Product';
    private const DEFAULT_BRANCH_ID = 473;
    private ProgressBar $bar;
    public Collection $groceryCategoriesIds;

    public function __construct()
    {
        $this->beginsAt = Carbon::parse('2020-12-25')->setTimeFromTimeString('00:00');
        parent::__construct();
    }

    public function handle(): void
    {
//        $img = 'https://tiptop-backend-production.s3.eu-central-1.amazonaws.com/media/dishes/9041/التونسا-خميرة.jpg';
        $this->showChoice();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if ($this->modelName === 'Branch') {
            $this->insertDefaultBranch();
        } elseif ($this->modelName === 'Product') {
            $this->getGroceryCategories();
            Product::truncate();
            ProductTranslation::truncate();
            $oldProducts = OldProduct::where('created_at', '>=', $this->beginsAt)
                                     ->orderBy('created_at')
                                     ->where('restaurant_id', 269)
                                     ->take(500)->get();
            $this->bar = $this->output->createProgressBar($oldProducts->count());
            $this->bar->start();
            foreach ($oldProducts as $oldProduct) {
                $this->insertProducts($oldProduct);
                $this->bar->advance();
            }
            $this->bar->finish();
        }
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
                ['Branch', 'Product'],
                1
            );
        }
    }

    private function addSingleImage($newModel, string $oldModelName, int $oldModelId, $imageCollection = 'cover'): void
    {
        $oldMediaData = OldMedia::where([
            ['model_type', "Modules\\Jo3aan\\Entities\\$oldModelName"], ['model_id', $oldModelId]
        ])->first();
        if ( ! is_null($oldMediaData) && $oldMediaData->id !== 9041) {
            $s3Url = $oldMediaData->getProductS3Url($oldMediaData->id, $oldMediaData->file_name);
            $newModel->addMediaFromUrl($s3Url)->toMediaCollection($imageCollection);
        }
    }

    private function insertDefaultBranch(): void
    {
        Branch::truncate();
        BranchTranslation::truncate();
        $first = OldBranch::find(self::DEFAULT_BRANCH_ID);
        $this->bar = $this->output->createProgressBar($first->count());
        $tempBranch = [];
        $this->bar->start();
        $this->bar->advance();
        foreach (OldBranch::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempBranch[$newModelKey] = $first->{$oldModelKey};
        }
        $tempBranch['uuid'] = $this->getUuidString();
        $tempBranch['chain_id'] = 1;
        $tempBranch['creator_id'] = 1;
        $tempBranch['editor_id'] = 1;
        $tempBranch['region_id'] = 2;
        $tempBranch['city_id'] = 1;
        $tempBranch['type'] = Branch::CHANNEL_GROCERY_OBJECT;
        $tempBranch['status'] = 2;
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
            $this->addSingleImage($freshBranch, 'Dish', $first->id, 'cover');
        }
        $this->bar->finish();
    }


    private function getUuidString(): string
    {
        return Controller::uuid().mt_rand(10, 99);
    }


    private function insertProducts(OldProduct $oldProduct)
    {
        $tempProduct = [];
        foreach (OldProduct::attributesComparing() as $oldModelKey => $newModelKey) {
            $tempProduct[$newModelKey] = $oldProduct->{$oldModelKey};
        }
        $tempProduct['uuid'] = $this->getUuidString();
        $tempProduct['chain_id'] = 1;
        $tempProduct['branch_id'] = self::DEFAULT_BRANCH_ID;
        $tempProduct['creator_id'] = 1;
        $tempProduct['editor_id'] = 1;
        $tempProduct['category_id'] = $this->groceryCategoriesIds->random(1)->first();
        $tempProduct['status'] = 2;
        $tempProduct['type'] = 1;
        $tempProduct['available_quantity'] = 100;
        $tempProduct['price_discount_by_percentage'] = $oldProduct->discount_type === OldProduct::TYPE_DISCOUNT_PERCENTAGE;
        $isInserted = Product::insert($tempProduct);
        if ($isInserted) {
            $freshProduct = Product::find($oldProduct->id);
            $randomCount = array_rand(array_flip([1, 2, 3]));
            $groceryCategoriesIds = $this->groceryCategoriesIds->random($randomCount);
            $freshProduct->categories()->sync($groceryCategoriesIds);
            foreach ($oldProduct->translations as $translation) {
                $attributesComparing = OldProductTranslation::attributesComparing();
                $tempTranslation = [];
                foreach ($attributesComparing as $oldAttribute => $newAttribute) {
                    $tempTranslation[$newAttribute] = $translation->{$oldAttribute};
                }
                ProductTranslation::insert($tempTranslation);
            }
            $this->addSingleImage($freshProduct, 'Dish', $oldProduct->id, 'cover');
        }
    }

    private function storeLocation($first, $freshBranch)
    {
        $location = new Location();
        $location->creator_id = $location->editor_id = auth()->id();
        $location->contactable_id = $freshBranch->id;
        $location->contactable_type = Branch::class;
        $location->type = Location::TYPE_CONTACT;
        $location->name = $first->contact_name;
        $location->emails = $first->contact_email;
        $location->phones = $first->contact_phone_1;
        $location->save();
    }

    private function getGroceryCategories(): void
    {
        $this->groceryCategoriesIds = Taxonomy::groceryCategories()->whereNotNull('parent_id')->pluck('id');
    }

    public function remoteFileExists($url): bool
    {
        return \Http::get($url)->successful();
    }
}
