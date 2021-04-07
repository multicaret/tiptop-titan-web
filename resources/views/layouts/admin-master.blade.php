<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ localization()->getCurrentLocaleDirection() }}"
      class="light-style layout-fixed" id="layout">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Backoffice Panel - MultiCaret">
    <meta name="author" content="MultiCaret">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{url('favicon.png')}}">

    <title>{{__('Dashboard')}} - @yield('title', null)</title>

    <!-- Main font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900"
          rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('/admin-assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin-assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin-assets/fonts/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin-assets/fonts/linearicons.css') }}">
    {{--    <link rel="stylesheet" href="{{ asset('/admin-assets/fonts/open-iconic.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('/admin-assets/fonts/pe-icon-7-stroke.css') }}">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="/admin-assets/css/rtl/bootstrap.css" class="theme-settings-bootstrap-css">
    <link rel="stylesheet" href="/admin-assets/css/rtl/appwork.css" class="theme-settings-appwork-css">
    <link rel="stylesheet" href="/admin-assets/css/rtl/theme-shadow.css" class="theme-settings-theme-css">
    <link rel="stylesheet" href="/admin-assets/css/rtl/colors.css" class="theme-settings-colors-css">
    <link rel="stylesheet" href="/admin-assets/css/rtl/uikit.css">

    <link rel="stylesheet" href="{{ asset('/admin-assets/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin-assets/libs/select2/select2.css') }}">

    <!-- Load polyfills -->
    <script src="{{ asset('/admin-assets/js/polyfills.js') }}"></script>
    <script>document['documentMode'] === 10 && document.write('<script src="https://polyfill.io/v3/polyfill.min.js?features=Intl.~locale.en"><\/script>')</script>

    <!-- Layout helpers -->
    <script src="/admin-assets/js/polyfills.js"></script>
    <script>document['documentMode'] === 10 && document.write('<script src="https://polyfill.io/v3/polyfill.min.js?features=Intl.~locale.en"><\/script>')</script>

    <script src="/admin-assets/js/material-ripple.js"></script>
    <script src="/admin-assets/js/layout-helpers.js"></script>
    @if(auth()->check() && auth()->user()->id !== 1)
        <script src="/admin-assets/js/theme-settings.js"></script>
        <script>
            window.themeSettings = new ThemeSettings({
                cssPath: '/admin-assets/css/rtl/',
                themesPath: '/admin-assets/css/rtl/'
            });
        </script>
    @endif

<!-- Core scripts -->
    <script src="/admin-assets/js/pace.js"></script>
    <!-- Libs -->
    <!-- `perfect-scrollbar` library required by SideNav plugin -->
    <link rel="stylesheet" href="{{ asset('/admin-assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'env' => app()->environment(),
            'default_locale' => localization()->getDefaultLocale(),
            'locale' => localization()->getCurrentLocale(),
            'dir' => localization()->getCurrentLocaleDirection(),
            'authenticated' => auth()->check(),
            'domain' => url('/'),
            'translations' => [
                'delete_confirmation_title' => trans('strings.delete_confirmation_title'),
                'delete_confirmation_message' => trans('strings.delete_confirmation_message'),
                'delete_confirmation_btn' => trans('strings.delete_confirmation_btn'),
                'deleted_title' => trans('strings.deleted_title'),
                'deleted_message' => trans('strings.deleted_message'),
                'cancel_btn_text' => trans('strings.cancel_btn_text'),
                'record_already_exists' => trans('strings.record_already_exists'),
                'mark_as_unread' => trans('strings.make_as_unread'),
                'mark_as_read' => trans('strings.make_as_read'),
                'saved' => trans('strings.saved'),
                'error' => trans('strings.error'),
                'working_day_0' => trans('strings.working_day_1'),
                'working_day_1' => trans('strings.working_day_2'),
                'working_day_2' => trans('strings.working_day_3'),
                'working_day_3' => trans('strings.working_day_4'),
                'working_day_4' => trans('strings.working_day_5'),
                'working_day_5' => trans('strings.working_day_6'),
                'working_day_6' => trans('strings.working_day_7'),
                'swal' => [
                    'title',
                    'text' => '',
                    'confirmButtonText'=> '',
                ],
            ],
            'routes' => [
                'mediaStore' => route('admin.media.store'),
            ],
            'tinyMce' => [
                'plugins' => "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor",
                'toolbar1' => "insertfile undo redo | fontsizeselect styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                'options' => [
                    'language' => localization()->getCurrentLocale(),
                    'directionality' => localization()->getCurrentLocaleDirection(),
                ],
            ]
        ]) !!};
    </script>

    @stack('styles')

<!-- Application stylesheets -->
    <link rel="stylesheet" href="{{ asset('/css/admin.css') }}">
    @if(localization()->getCurrentLocaleDirection() == 'rtl')
        <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap" rel="stylesheet">
        <style>
            body,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
                /*            i:not(.fa),
                            i:not(.ion),
                            span:not(.ion),
                            span:not(.fa),
                            span:not(.far),
                            span:not(.fas),*/
            ul,
            li,
            a,
            input,
            textarea,
            p {
                font-family: 'Almarai', sans-serif !important;
            }
        </style>
    @endif

    @stack('head')
</head>
<body>

<div class="page-loader">
    <div class="bg-primary"></div>
</div>

<div id="vue-app">
    @yield('container')
</div>



<!-- Core scripts -->
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('/admin-assets/js/sidenav.js') }}"></script>

<!-- Libs -->
<script src="{{ asset('/admin-assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="https://unpkg.com/clipboard@2/dist/clipboard.min.js"></script>
<script>
    $('document').ready(function () {
        var clipboard;
        if (ClipboardJS.isSupported()) {
            clipboard = new ClipboardJS('.clipboard-btn');
        } else {
            $('.clipboard-btn').prop('disabled', true);
        }
        if (clipboard) {
            clipboard.on('success', function (e) {
                console.info('Text:', e.text);
                e.clearSelection();
                const capitalizeAction = e.action.charAt(0).toUpperCase() + e.action.slice(1);
                const message = capitalizeAction + ' ' + @json(trans('strings.successfully_done'));
                window.toast.fire({
                    icon: 'success',
                    title: message,
                });
            });
        }

    });
</script>

@if(session()->has('message'))
    <script type="text/javascript">
        window.toast.fire({
            icon: "{{ strtolower(session('message')['type']) }}",
            type: "{{ strtolower(session('message')['type']) }}",
            title: "{{__(ucfirst(session('message')['type']))}}",
            text: "{{ session('message')['text'] }}",
        });
    </script>
@elseif(session()->has('confirm'))
    <script type="text/javascript">
        swal.fire({
            title: "@lang('strings.'. ucfirst(session('confirm')['type']))",
            text: "{!! session('confirm')['text'] !!}",
            type: "{{ strtolower(session('confirm')['type']) }}",
            confirmButtonText: "{{ session('confirm')['confirmButtonText'] }}",
            cancelButtonText: "{{ session('confirm')['cancelButtonText'] }}",
            showCancelButton: true,
        }).then((result) => {
            if (result.value) {
                window.location.href = "{{ session('confirm')['confirmLink'] }}";
            } else if (result.dismiss === swal.DismissReason.cancel) {
                window.location.href = "{{ session('confirm')['cancelLink'] }}";
            }
        })
    </script>
@elseif(session()->has('multiple-buttons-message'))
    <script type="text/javascript">
        let buttonsStr = '';
        let buttons = @json(session('multiple-buttons-message')['buttons']);
        buttons.forEach(function (button) {
            buttonsStr += "<a class='btn " + button.class + "' style='margin: 10px' href='" + button.link + "'>" + button.title + "</a>";
        });

        swal.fire({
            title: "@lang('strings.'. ucfirst(session('multiple-buttons-message')['type']))",
            text: "{!! session('multiple-buttons-message')['text'] !!}",
            type: "{{ strtolower(session('multiple-buttons-message')['type']) }}",
            showCloseButton: false,
            showConfirmButton: false,
            html: "{{ session('multiple-buttons-message')['text'] }}<br><br>" + buttonsStr
        });
    </script>
@endif

<script>
    $('[data-toggle="tooltip"]').tooltip();
</script>
@stack('scripts')
</body>
</html>
