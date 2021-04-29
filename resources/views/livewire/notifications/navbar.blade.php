<style>
    .notifications-list {
        max-height: 250px;
        overflow-y: scroll;
    }
</style>
<div>
    <div class="demo-navbar-notifications nav-item dropdown mr-lg-3">
        @if($unreadUserNotifications->count())
            <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown" aria-expanded="false">
                <i class="ion ion-md-notifications-outline navbar-icon align-middle"></i>
                <span class="badge badge-primary badge-dot indicator"></span>
                <span class="d-lg-none align-middle">&nbsp; Notifications</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right mt-0">
                <div class="bg-primary text-center text-white font-weight-bold p-3">
                    {{$unreadUserNotifications->count()}}
                    New Notifications
                </div>
                <div class="list-group list-group-flush notifications-list">
                    @foreach($unreadUserNotifications as $notification)
                        <div>@include('admin.notifications.'. \Illuminate\Support\Str::kebab(last(explode('\\',$notification->type))))</div>
                    @endforeach
                </div>

                <a href="{{route('admin.notifications.index')}}" class="d-block text-center text-light small p-2 my-1">
                    Show all notifications
                </a>
            </div>
        @else
            <a class="nav-link dropdown-toggle hide-arrow" href="{{route('admin.notifications.index')}}">
                <i class="ion ion-md-notifications-outline navbar-icon align-middle"></i>
                <span class="d-lg-none align-middle">&nbsp; Notifications</span>
            </a>
        @endif
    </div>

</div>
