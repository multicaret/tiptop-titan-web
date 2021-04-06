<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal my-auto fade" id="orderShowModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            @if($selectedOrder)
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">#{{$selectedOrder->reference_code}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body bg-light">
                        {{--                    <h1>Modal Body</h1>--}}

                        {{--<div class="form-group">
                            <label for="reference-code">Status</label>
                            <select type="text" inputmode="numeric" --}}{{--wire:model.debounce.300ms="referenceCode"--}}{{--
                            class="form-control" id="reference-code">
                                <option>Preparing</option>
                                <option>Cancelled</option>
                            </select>
                        </div>--}}
                        <div class="nav-tabs-top nav-responsive-xl">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#navs-bottom-responsive-link-1">
                                        <i class="fas fa-shopping-basket"></i>&nbsp;Order Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-2">
                                        <i class="fas fa-user"></i>&nbsp;Customer Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#navs-bottom-responsive-link-2">
                                        <i class="fas fa-code-branch"></i>&nbsp;Branch Details
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="navs-bottom-responsive-link-1">
                                    <div class="card-body">
                                        <p>Stacked on extra small screens</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="navs-bottom-responsive-link-2">
                                    <div class="card-body">
                                        <p>Tab content</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
