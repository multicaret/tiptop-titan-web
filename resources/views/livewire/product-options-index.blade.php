<div>
    <button class="btn btn-sm btn-outline-primary" wire:click="addNewOption">
        <i class="fas fa-plus"></i>
        Add New Option
    </button>
    <div class="row">
        @foreach($product->options as $option)
            <livewire:product-option :option="$option" :key="'product-option-'.$option->id"/>
        @endforeach
    </div>
</div>
