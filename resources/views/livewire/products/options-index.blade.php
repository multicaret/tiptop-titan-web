<div>
    @foreach($product->options as $option)
        <livewire:products.product-option :option="$option" :key="'product-option-'.$option->id"/>
    @endforeach
    <div class="row">
        <div class="col-12">
            <button class="btn btn-sm btn-outline-primary" wire:click="addNewOption">
                <i class="fas fa-plus"></i>
                Add New Option
            </button>
        </div>
    </div>
</div>
