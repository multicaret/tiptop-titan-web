
<div class="nav-tabs-top nav-responsive-xl">
    <ul class="nav nav-tabs nav-justified">
        <li class="nav-item ">
            <a class="nav-link"  href="{{route('admin.orders.index')}}">
                <i class="fas fa-edit"></i>&nbsp;TipTop Orders
                <div class="pl-1 ml-auto  d-inline-block" style="font-size:14px;">
                    <div class="badge badge-danger d-inline-block">
                        {{$tiptopOrdersCount}}
                    </div>
                </div>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active " data-toggle="tab" href="">
                <i class="far fa-clock"></i>&nbsp;Jet Orders
            </a>
        </li>
    </ul>
    <div class="tab-content mt-3">
        <div class="tab-pane fade " id="settings-tab">

        </div>
        <div class="tab-pane fade active show" id="working-hours-tab">
            <div class="card-body">
                <livewire:orders.jet-orders-table/>

            </div>
        </div>

    </div>
</div>
@push('styles')
    <style>
        .nav-link.active{
            box-shadow: 0 1rem 3rem rgb(24 28 33 / 18%) !important
        }
    </style>
@endpush
