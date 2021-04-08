<div>
    <div wire:ignore.self class="modal my-auto fade" id="optionsModal" tabindex="-1" role="dialog"
         aria-labelledby="order-show-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            @if($selectedProduct)
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="order-show-modal">#
                            {{$selectedProduct->title}}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body bg-light">

                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click.prevent="$set('showModal','false')" class="btn btn-secondary"
                                data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
