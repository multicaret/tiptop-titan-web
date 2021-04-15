<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Chain;
use App\Models\OldModels\OldBranch;
use App\Models\OldModels\OldCategory;
use App\Models\OldModels\OldChain;
use App\Models\OldModels\OldMedia;
use App\Models\OldModels\OldProduct;
use App\Models\Preference;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Taxonomy;
use App\Models\TaxonomyTranslation;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Translation\FileLoader;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SyncChains extends Command
{
    protected $signature = 'datum:sync-chains {--id=*}';
    protected $description = 'Clone All Chain Products To All Chain Branches';
    private array $chainIds = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasOption('id')) {
            $options = $this->options();
            if (isset($options['id'])) {
                $this->chainIds = $options['id'];
            }
        } else {
            $this->warn('Option id is missing!.');
        }
    }


    public function handle()
    {
        if ( ! empty($this->chainIds) && ! is_null($this->chainIds[0])) {
            foreach ($this->chainIds as $chainId) {
                $allChainBranchesIds = Branch::whereChainId($chainId)->pluck('id');
                foreach ($allChainBranchesIds as $chainBranchId) {
                    $chainProducts = Product::with('barcodes')->whereChainId($chainId)
                                            ->whereNull('branch_id')->get();
                    try {
                        $this->cloneChainProducts($chainProducts, $chainBranchId);
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                }
            }
        } else {
            $this->warn('Select chain id first');
        }
        $this->info('Sync chain is finished 👌.');
    }

    private function cloneChainProducts($chainProducts, $branchId)
    {
        foreach ($chainProducts as $originalProduct) {
            $newProduct = $originalProduct->replicateWithTranslations();
            $newProduct->branch_id = $branchId;
            $newProduct->push();
            // Todo: check barcode relation.
            $newProduct->categories()->sync($originalProduct->categories->pluck('id'));
        }
    }
}
