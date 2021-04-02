<div>
    <div class="mb-4">
        <h3>
            Orders
        </h3>
        <div class="row">
            <div class="col-4">
                {{$pendingOrdersCount}}
            </div>
            <div class="col-4">
                <h3>{{ now()->format(config('defaults.date.normal_format')) }}</h3>
                <h4>{{ now()->format('H:i') }}</h4>
            </div>
            <div class="col-4">
                <div class="media align-items-center py-3 mb-3">
                    <img src="{{ $auth->avatar }}" alt="{{ $auth->name }}" class="d-block ui-w-100 rounded-circle">
                    <div class="media-body ml-4">
                        <h4 class="font-weight-bold mb-0">
                            {{ $auth->name }}
                            <span class="text-muted font-weight-normal">{{ '@'.$auth->username }}</span>
                        </h4>
                        <div class="text-muted mb-2">ID: {{ $auth->id }}</div>
                        {{--                <a href="javascript:void(0)" class="btn btn-primary btn-sm">Edit</a>&nbsp;--}}
                        {{--                <a href="javascript:void(0)" class="btn btn-default btn-sm">Profile</a>&nbsp;--}}
                        {{--                <a href="javascript:void(0)" class="btn btn-default btn-sm icon-btn"><i class="ion ion-md-mail"></i></a>--}}
                    </div>
                </div>
            </div>
        </div>
        <livewire:orders-table/>
    </div>
</div>
