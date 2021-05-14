<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Chain;
use App\Models\Product;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SyncChains extends Command
{
    protected $signature = 'datum:sync-chains {--id=*} {--branchIds=*}';
    protected $description = 'Clone All Chain Products To All Chain Branches';
    private array $chainIds = [];
    private array $branchIds = [];

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
            if (isset($options['branchIds'])) {
                $this->branchIds = $options['branchIds'];
            }
        } else {
            $this->warn('Option id is missing!.');
        }
    }


    public function handle()
    {
        $syncProductsStatus = '';
        if ( ! empty($this->chainIds) && ! is_null($this->chainIds[0])) {
            foreach ($this->chainIds as $chainId) {
                if (is_null($this->branchIds)) {
                    $branchesIds = Branch::whereChainId($chainId)->pluck('id');
                } else {
                    $branchesIds = $this->branchIds;
                }
                foreach ($branchesIds as $chainBranchId) {
                    $chainProducts = Product::whereChainId($chainId)
                                            ->whereNull('branch_id')
                                            ->get();
                    try {
                        $syncProductsStatus = $this->cloneChainProducts($chainProducts, $chainBranchId);
                        $this->updateChainSyncStatus($chainId);
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                }
            }
        } else {
            $this->warn('Select chain id first 🚧');
        }
        if ( ! $syncProductsStatus) {
            $this->info('Sync chain is finished 👌');
        } else {
            $this->info($syncProductsStatus);
        }
    }

    private function cloneChainProducts($chainProducts, $branchId)
    {
        if (Product::where('branch_id', $branchId)->count() == 0) {
            foreach ($chainProducts as $originalProduct) {
                $newProduct = $originalProduct->replicateWithTranslations();
                $newProduct->branch_id = $branchId;
                $newProduct->cloned_from_product_id = $originalProduct->id;
                $newProduct->push();
                // Todo: check barcode relation.
                $newProduct->categories()->sync($originalProduct->categories->pluck('id'));
            }
        } else {
            return '🚨 Trying to sync to a branch with already products 🚨';
        }
    }

    private function updateChainSyncStatus($chainId)
    {
        $chain = Chain::whereId($chainId)->first();
        if ( ! $chain->is_synced) {
            $chain->is_synced = 1;
            $chain->save();
        }
    }
}
