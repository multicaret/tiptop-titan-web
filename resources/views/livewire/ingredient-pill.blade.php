<div class="badge badge-pill badge-primary p-2 px-3 text-secondary">
    {{$ingredient->title}}
    &nbsp;&nbsp;
    @if($isPriceShown)
        <input type="text" numeric placeholder="0" wire:model.lazy="price" class="price">
    @endif
    <span class="remove py-1 cursor-pointer"
          wire:click="removeIngredient({{$ingredient->id}})">
            <i class="fas fa-times"></i>
    </span>
</div>
