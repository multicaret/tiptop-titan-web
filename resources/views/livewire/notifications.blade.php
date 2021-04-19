<div>
    {{-- Be like water. --}}

    <div class="demo-navbar-notifications nav-item dropdown mr-lg-3">
        <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown" aria-expanded="false">
            <i class="ion ion-md-notifications-outline navbar-icon align-middle"></i>
            <span class="badge badge-primary badge-dot indicator"></span>
            <span class="d-lg-none align-middle">&nbsp; Notifications</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="bg-primary text-center text-white font-weight-bold p-3">
                4 New Notifications
            </div>
            <div class="list-group list-group-flush">
                @foreach($userNotifications as $notification)
                    <div>@include('admin.notifications.'. \Illuminate\Support\Str::kebab(last(explode('\\',$notification->type))))</div>
                @endforeach

                {{--<a href="javascript:void(0)"
                   class="list-group-item list-group-item-action media d-flex align-items-center">
                    <div class="ui-icon ui-icon-sm ion ion-md-person-add bg-info border-0 text-white"></div>
                    <div class="media-body line-height-condenced ml-3">
                        <div class="text-body">You have <strong>4</strong> new followers</div>
                        <div class="text-light small mt-1">
                            Phasellus nunc nisl, posuere cursus pretium nec, dictum vehicula tellus.
                        </div>
                    </div>
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action media d-flex align-items-center">
                    <div class="ui-icon ui-icon-sm ion ion-md-power bg-danger border-0 text-white"></div>
                    <div class="media-body line-height-condenced ml-3">
                        <div class="text-body">Server restarted</div>
                        <div class="text-light small mt-1">
                            19h ago
                        </div>
                    </div>
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action media d-flex align-items-center">
                    <div class="ui-icon ui-icon-sm ion ion-md-warning bg-warning border-0 text-body"></div>
                    <div class="media-body line-height-condenced ml-3">
                        <div class="text-body">99% server load</div>
                        <div class="text-light small mt-1">
                            Etiam nec fringilla magna. Donec mi metus.
                        </div>
                        <div class="text-light small mt-1">
                            20h ago
                        </div>
                    </div>
                </a>--}}
            </div>

            <a href="javascript:void(0)" class="d-block text-center text-light small p-2 my-1">Show all
                                                                                               notifications</a>
        </div>
    </div>

</div>
