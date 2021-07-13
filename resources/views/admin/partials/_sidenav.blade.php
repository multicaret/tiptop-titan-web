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
    <div class="app-brand demo ml-2">
        {{--
         use this class (.side-nav-logo) if you want to change the logo if sidenav collapsed
        --}}
        <a href="{{route('admin.index')}}" class="app-brand-text demo sidenav-text font-weight-normal">
            <img src="/images/logo.svg" alt="@preferences('app_title')" width="135px"
                 class="">
        </a>
        <a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large">
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
                        <i class="sidenav-icon text-primary {{ $link['icon'] }} fa-fw" style="font-size:1.4em"></i>
                        &nbsp;<div class="font-weight-bold">{{ $link['title'] }}</div>
                        @if(array_key_exists('countPrimary',$link) || array_key_exists('countDanger',$link))
                            <div class="pl-1 ml-auto" style="font-size:12px;">
                                @if(array_key_exists('countDanger',$link) && $link['countDanger'])
                                    <div class="badge badge-danger opacity-50">
                                        {{$link['countDanger']}}
                                    </div>
                                @endif
                                @if(array_key_exists('countPrimary',$link) && $link['countPrimary'])
                                    <div class="badge badge-primary">
                                        {{$link['countPrimary']}}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </a>
                    @if(isset($link['subChildren']))
                        <ul class="sidenav-menu">
                            @foreach($link['subChildren'] as $childItem)
                                <li class="sidenav-item {{$childItem['status']}}">
                                    <a href="{{$childItem['route']}}"
                                       class="sidenav-link {{\Str::title(request()->input('type')) === $childItem['title']? 'active': '' }}">
                                        <i class="mr-2 text-secondary {{ $childItem['icon'] }} fa-fw"
                                           style="font-size:1.4em"></i>
                                        <div class="font-weight-semibold">{{$childItem['title']}}</div>
                                        @if(array_key_exists('countPrimary',$childItem) || array_key_exists('countDanger',$childItem))
                                            <div class="pl-1 ml-auto" style="font-size:12px;">
                                                @if(array_key_exists('countDanger',$childItem) && $childItem['countDanger'])
                                                    <div class="badge badge-danger opacity-50">
                                                        {{$childItem['countDanger']}}
                                                    </div>
                                                @endif
                                                @if(array_key_exists('countPrimary',$childItem) && $childItem['countPrimary'])
                                                    <div class="badge badge-primary">
                                                        {{$childItem['countPrimary']}}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
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
