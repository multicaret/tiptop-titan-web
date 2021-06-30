<header id="header"
        class="header header-box-shadow-on-scroll header-abs-top header-white-nav-links-lg header-bg-transparent header-show-hide"
        data-hs-header-options='{
            "fixMoment": 1000,
            "fixEffect": "slide"
          }'>
    <div class="header-section">
        <!-- Topbar -->
        <div class="container header-hide-content d-none d-lg-block pt-2">
            <div class="d-flex align-items-center">
                <div class="ml-auto">
                    <!-- Jump To -->
                    <div class="hs-unfold d-sm-none mr-2">
                        <a class="js-hs-unfold-invoker dropdown-nav-link dropdown-toggle d-flex align-items-center"
                           href="javascript:;"
                           data-hs-unfold-options='{
                  "target": "#jumpToDropdown",
                  "type": "css-animation",
                  "event": "hover",
                  "hideOnScroll": "true"
                 }'>
                            Jump to
                        </a>

                        <div id="jumpToDropdown" class="hs-unfold-content dropdown-menu">
                            <a class="dropdown-item" href="../pages/faq.html">Help</a>
                            <a class="dropdown-item" href="../pages/contacts-agency.html">Contacts</a>
                        </div>
                    </div>
                    <!-- End Jump To -->

                    <!-- Links -->
                    <!--<div class="nav nav-sm nav-y-0 d-none d-sm-flex ml-sm-auto">
                            <a class="nav-link" href="../pages/faq.html">Help</a>
                            <a class="nav-link" href="../pages/contacts-agency.html">Contacts</a>
                    </div>-->
                    <!-- End Links -->
                </div>
            </div>
        </div>
        <!-- End Topbar -->

        <div id="logoAndNav" class="container">
            <!-- Nav -->
            <nav class="js-mega-menu navbar navbar-expand-lg">
                <!-- White Logo -->
                <a class="navbar-brand navbar-brand-default" href="{{url('/')}}" aria-label="Front">
                    <img src="/frontend-assets/images/logo-white.png" alt="TipTop Logo">
                </a>
                <!-- End White Logo -->

                <!-- Default Logo -->
                <a class="navbar-brand navbar-brand-on-scroll" href="{{url('/')}}" aria-label="Front">
                    <img src="/frontend-assets/images/logo.png" alt="TipTop Logo">
                </a>
                <!-- End Default Logo -->

                <!-- Default Logo -->
                <a class="navbar-brand navbar-brand-collapsed" href="{{url('/')}}" aria-label="Front">
                    <img src="/frontend-assets/images/logo.png" alt="TipTop Logo">
                </a>
                <!-- End Default Logo -->

                <!-- Responsive Toggle Button -->
                <button type="button" class="navbar-toggler btn btn-icon btn-sm rounded-circle"
                        aria-label="Toggle navigation"
                        aria-expanded="false"
                        aria-controls="navBar"
                        data-toggle="collapse"
                        data-target="#navBar">
            <span class="navbar-toggler-default">
              <svg width="14" height="14" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor"
                      d="M17.4,6.2H0.6C0.3,6.2,0,5.9,0,5.5V4.1c0-0.4,0.3-0.7,0.6-0.7h16.9c0.3,0,0.6,0.3,0.6,0.7v1.4C18,5.9,17.7,6.2,17.4,6.2z M17.4,14.1H0.6c-0.3,0-0.6-0.3-0.6-0.7V12c0-0.4,0.3-0.7,0.6-0.7h16.9c0.3,0,0.6,0.3,0.6,0.7v1.4C18,13.7,17.7,14.1,17.4,14.1z"/>
              </svg>
            </span>
                    <span class="navbar-toggler-toggled">
              <svg width="14" height="14" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor"
                      d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z"/>
              </svg>
            </span>
                </button>
                <!-- End Responsive Toggle Button -->

                <!-- Navigation -->
                <div id="navBar" class="collapse navbar-collapse">
                    <div class="navbar-body header-abs-top-inner">
                        <ul class="navbar-nav">


                            <!--<li class="header-nav-item active">
                                    <a class="nav-link header-nav-link" href="#homeSection">Home</a>
                            </li>-->
                            <li class="header-nav-item">
                                <a class="nav-link header-nav-link" href="#section1">
                                    {{(__('strings.how_to_use'))}}
                                </a>
                            </li>
                            <li class="header-nav-item">
                                <a class="nav-link header-nav-link" href="#section2">
                                    {{(__('strings.app_screenshots'))}}
                                </a>
                            </li>
                            <li class="header-nav-item">
                                <a class="nav-link header-nav-link" href="{{route('blog.index')}}">
                                    {{__('strings.blog')}}
                                </a>
                            </li>
                            <li class="navbar-nav-last-item">
                                <a class="btn btn-sm btn-indigo btn-pill transition-3d-hover" href="#lead-form-section">
                                    {{(__('strings.download_app'))}}
                                </a>
                            </li>
                            @if(app()->getLocale() != 'ku')
                                <li class="navbar-nav-last-item">
                                    <a class="btn btn-sm btn-white btn-pill transition-3d-hover"
                                       href="{{ localized_route('home',[],'ku') }}">
                                        كوردى
                                    </a>
                                </li>
                            @endif
                            @if(app()->getLocale() != 'en')
                                <li class="navbar-nav-last-item">
                                    <a class="btn btn-sm btn-white btn-pill transition-3d-hover"
                                       href="{{ localized_route('home',[],'en') }}">
                                        English
                                    </a>
                                </li>
                            @endif
                            @if(app()->getLocale() != 'ar')
                                <li class="navbar-nav-last-item">
                                    <a class="btn btn-sm btn-white btn-pill transition-3d-hover"
                                       href="{{ localized_route('home',[],'ar') }}">
                                        العربية
                                    </a>
                                </li>
                            @endif



                        </ul>
                    </div>
                </div>
                <!-- End Navigation -->
            </nav>
            <!-- End Nav -->
        </div>
    </div>
</header>
