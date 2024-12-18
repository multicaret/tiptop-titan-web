<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center container-p-x bg-white"
     id="layout-navbar">

    <!-- Brand -->
    <a href="{{ route('home') }}" class="navbar-brand">Front Office</a>

<!-- Navbar toggle -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse" id="layout-navbar-collapse">
        <!-- Divider -->
        <hr class="d-lg-none w-100 my-2">

        <div class="navbar-nav align-items-lg-center">
            {{--
            <!-- Search -->
            <label class="nav-item navbar-text navbar-search-box p-0 active">
                <i class="ion ion-ios-search navbar-icon align-middle"></i>
                <span class="navbar-search-input pl-2">
                <input type="text" class="form-control navbar-text mx-2" placeholder="Search..." style="width:200px">
              </span>
            </label>--}}
        </div>

        <div class="navbar-nav align-items-lg-center ml-auto">
            {{--<div class="demo-navbar-notifications nav-item dropdown mr-lg-3">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown">
                    <i class="ion ion-md-notifications-outline navbar-icon align-middle"></i>
                    <span class="badge badge-primary badge-dot indicator"></span>
                    <span class="d-lg-none align-middle">&nbsp; Notifications</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="bg-primary text-center text-white font-weight-bold p-3">
                        4 New Notifications
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="javascript:void(0)"
                           class="list-group-item list-group-item-action media d-flex align-items-center">
                            <div class="ui-icon ui-icon-sm ion ion-md-home bg-secondary border-0 text-white"></div>
                            <div class="media-body line-height-condenced ml-3">
                                <div class="text-body">Login from 192.168.1.1</div>
                                <div class="text-light small mt-1">
                                    Aliquam ex eros, imperdiet vulputate hendrerit et.
                                </div>
                                <div class="text-light small mt-1">12h ago</div>
                            </div>
                        </a>

                        <a href="javascript:void(0)"
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
                        </a>
                    </div>

                    <a href="javascript:void(0)" class="d-block text-center text-light small p-2 my-1">Show all
                                                                                                       notifications</a>
                </div>
            </div>--}}

            {{--<div class="demo-navbar-messages nav-item dropdown mr-lg-3">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-toggle="dropdown">
                    <i class="ion ion-ios-mail navbar-icon align-middle"></i>
                    <span class="badge badge-primary badge-dot indicator"></span>
                    <span class="d-lg-none align-middle">&nbsp; Messages</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="bg-primary text-center text-white font-weight-bold p-3">
                        4 New Messages
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="javascript:void(0)"
                           class="list-group-item list-group-item-action media d-flex align-items-center">
                            <img src="assets/img/avatars/6-small.png" class="d-block ui-w-40 rounded-circle" alt>
                            <div class="media-body ml-3">
                                <div class="text-body line-height-condenced">Sit meis deleniti eu, pri vidit meliore
                                                                             docendi ut.
                                </div>
                                <div class="text-light small mt-1">
                                    Mae Gibson &nbsp;·&nbsp; 58m ago
                                </div>
                            </div>
                        </a>

                        <a href="javascript:void(0)"
                           class="list-group-item list-group-item-action media d-flex align-items-center">
                            <img src="assets/img/avatars/4-small.png" class="d-block ui-w-40 rounded-circle" alt>
                            <div class="media-body ml-3">
                                <div class="text-body line-height-condenced">Mea et legere fuisset, ius amet purto
                                                                             luptatum te.
                                </div>
                                <div class="text-light small mt-1">
                                    Kenneth Frazier &nbsp;·&nbsp; 1h ago
                                </div>
                            </div>
                        </a>

                        <a href="javascript:void(0)"
                           class="list-group-item list-group-item-action media d-flex align-items-center">
                            <img src="assets/img/avatars/5-small.png" class="d-block ui-w-40 rounded-circle" alt>
                            <div class="media-body ml-3">
                                <div class="text-body line-height-condenced">Sit meis deleniti eu, pri vidit meliore
                                                                             docendi ut.
                                </div>
                                <div class="text-light small mt-1">
                                    Nelle Maxwell &nbsp;·&nbsp; 2h ago
                                </div>
                            </div>
                        </a>

                        <a href="javascript:void(0)"
                           class="list-group-item list-group-item-action media d-flex align-items-center">
                            <img src="assets/img/avatars/11-small.png" class="d-block ui-w-40 rounded-circle" alt>
                            <div class="media-body ml-3">
                                <div class="text-body line-height-condenced">Lorem ipsum dolor sit amet, vis erat
                                                                             denique in, dicunt prodesset te vix.
                                </div>
                                <div class="text-light small mt-1">
                                    Belle Ross &nbsp;·&nbsp; 5h ago
                                </div>
                            </div>
                        </a>
                    </div>

                    <a href="javascript:void(0)" class="d-block text-center text-light small p-2 my-1">Show all
                                                                                                       messages</a>
                </div>
            </div>--}}
            <livewire:navbar-notifications>
                <!-- Divider -->
                <div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|
                </div>

                <div class="demo-navbar-user nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                  <img src="{{$auth->avatar}}" alt class="d-block ui-w-30 rounded-circle">
                  <span class="px-1 mr-lg-2 ml-2 ml-lg-0">{{$auth->name}}</span>
                </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        {{--<a href="javascript:void(0)" class="dropdown-item"><i class="ion ion-ios-person text-lightest"></i>
                            &nbsp; My profile</a>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="ion ion-ios-mail text-lightest"></i>
                            &nbsp; Messages</a>--}}
                        <a href="{{ route('admin.users.edit',['role' => Str::lower($auth->roles[0]->first()->name),'user' => $auth]) }}"
                           class="dropdown-item"><i class="ion ion-md-settings text-lightest"></i>
                            &nbsp; Account settings</a>
                        <div class="dropdown-divider"></div>


                        <a href="#!" class="dropdown-item"
                           onclick="event.preventDefault();localStorage.clear();document.getElementById('logout-form').submit();">
                            <i class="ion ion-ios-log-out text-danger"></i>&nbsp;&nbsp; @lang('strings.logout')
                        </a>
                        <form id="logout-form" action="{{ localization()->localizeURL(route('logout')) }}"
                              method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
        </div>
    </div>

</nav>
