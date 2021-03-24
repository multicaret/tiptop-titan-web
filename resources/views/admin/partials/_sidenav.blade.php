<div id="layout-sidenav"
     class="layout-sidenav sidenav-vertical sidenav layout-fixed bg-sidenav-theme">
    <!-- Brand demo (see assets/css/demo/demo.css) -->
    <div class="app-brand demo">
        {{--
         use this class (.side-nav-logo) if you want to change the logo if sidenav collapsed
        --}}
        <img src="/images/logo.jpeg" alt="@preferences('app_title')" width="50px"
             class="">
        <a href="{{route('admin.index')}}" class="app-brand-text demo sidenav-text font-weight-normal ml-2">
            @preferences('app_title')
        </a>
        <a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
            <i class="ion ion-md-menu align-middle"></i>
        </a>
    </div>

    <div class="sidenav-divider mt-0"></div>
    <!-- Inner -->
    <ul class="sidenav-inner overflow-auto">
        @foreach($sidenavLinks as $section)
            @if(!empty($section['title']))
                <li class="sidenav-header small font-weight-semibold">{{ $section['title'] }}</li>
            @endif

            @foreach($section['children'] as $link)
                <li class="sidenav-item @active($link['route'])">
                    <a href="{{ $link['route'] }}" class="sidenav-link">
                        <i class="sidenav-icon text-primary {{ $link['icon'] }}"></i>
                        <div>{{ $link['title'] }}</div>
                    </a>
                </li>
            @endforeach
            <li class="sidenav-divider my-2"></li>
        @endforeach
        <li class="" style="margin-top: 100px">
        </li>
    </ul>
</div>
