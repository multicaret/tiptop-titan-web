@push('styles')
    <style>
        .sidenav-toggle::after {
            transform: translateY(-50%) rotate(
                225deg
            );
        }
    </style>
@endpush
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
                <li class="sidenav-item {{$link['status']}}">
                    <a href="{{ $link['route'] }}"
                       class="sidenav-link {{isset($link['subChildren']) ? 'sidenav-toggle': ''}}">
                        <i class="sidenav-icon text-primary {{ $link['icon'] }}" style="font-size:1.4em"></i>
                        &nbsp;<div>{{ $link['title'] }}</div>
                    </a>
                    @if(isset($link['subChildren']))
                        <ul class="sidenav-menu">
                            @foreach($link['subChildren'] as $childItem)
                                <li class="sidenav-item {{$childItem['status']}}">
                                    <a href="{{$childItem['route']}}"
                                       class="sidenav-link {{\Str::title(request()->input('type')) === $childItem['title']? 'active': ''}}">
                                        <div>{{$childItem['title']}}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
            <li class="sidenav-divider my-2"></li>
        @endforeach
        <li class="" style="margin-top: 100px">
        </li>
    </ul>
</div>

@push('scripts')
    <script>
        $('document').ready(function () {
            const $sidenav = $('.sidenav-menu');
            if ($sidenav.children('.active').length !== 0) {
                $sidenav.each(function () {
                    if ($(this).children('.active').length !== 0) {
                        $(this).parent().addClass('open');
                    }
                });
            }
        });
    </script>
@endpush
