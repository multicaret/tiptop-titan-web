<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ localization()->getCurrentLocaleDirection() }}">
<head>
    <!-- Title -->
    <title>@yield('title', 'TipTop')</title>

    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="google" content="notranslate">

    <meta name="description" content="اطلب من TipTop هسا واحصل على عروض وخصومات يومياً">
    <!--	<meta name="keywords" content=".....">-->

    <meta property="og:title" content="		اطلب من TipTop هسا واحصل على عروض وخصومات يومياً">
    <meta property="og:description" content="اطلب من TipTop هسا واحصل على عروض وخصومات يومياً">
    {{--    <meta property="og:type" content="landing page">--}}
    <meta property="og:site_name" content="TipTop">
    <meta property="og:url" content="{{ url('/') }}">


    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Font -->
    <!--	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&display=swap" rel="stylesheet">-->
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="/frontend-assets/vendor/font-awesome/css/all.min.css">
    <!--    <link rel="stylesheet" href="/frontend-assets/vendor/hs-mega-menu/dist/hs-mega-menu.min.css">-->
    <!--    <link rel="stylesheet" href="/frontend-assets/vendor/fancybox/dist/jquery.fancybox.min.css">-->
    <link rel="stylesheet" href="/frontend-assets/vendor/aos/dist/aos.css">

    <!-- CSS Front Template -->
    @if(localization()->getCurrentLocaleDirection() == 'rtl')
        <link rel="stylesheet" href="/frontend-assets/css/theme-rtl.css?v=3">
    @else
        <link rel="stylesheet" href="/frontend-assets/css/theme.css?v=3">
    @endif

    <script>
        dataLayer = [];
    </script>
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-TDHBZJX');</script>
    <!-- End Google Tag Manager -->
    <style>
        {!! $appPreferences['custom_css_head'] !!}
    </style>
    {!! $appPreferences['custom_code_head'] !!}
</head>
<body>


<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TDHBZJX"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->


<!-- ========== HEADER ========== -->
@include('frontend.partials._header')
<!-- ========== END HEADER ========== -->

<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main">
    @include('frontend.partials._hero')
    <div class="container">
        @yield('content')
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->
<!-- ========== FOOTER ========== -->
<footer class="container text-center space-1">
    <!-- Logo -->
    <a class="d-inline-flex align-items-center mb-2" href="index.html" aria-label="Front">
        <img class="brand" src="/frontend-assets/images/logo.png" alt="Logo">
    </a>
    <!-- End Logo -->

    <p class="small text-muted mb-0">
        {{ date('Y') }} © All rights reserved. Try Tiptop Kargo Ve Sanal Magazacilik Ticaret Limited
                        Sirketi.
    </p>
</footer>
<!-- ========== END FOOTER ========== -->

<!-- Go to Top -->
<a class="js-go-to go-to position-fixed" href="javascript:;" style="visibility: hidden;"
   data-hs-go-to-options='{
       "offsetTop": 700,
       "position": {
         "init": {
           "right": 15
         },
         "show": {
           "bottom": 15
         },
         "hide": {
           "bottom": -15
         }
       }
     }'>
    <i class="fas fa-angle-up"></i>
</a>
<!-- End Go to Top -->

<!-- JS Global Compulsory -->
<script src="/frontend-assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="/frontend-assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
<script src="/frontend-assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/frontend-assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>

<!-- JS Implementing Plugins -->
<script src="/frontend-assets/vendor/hs-header/dist/hs-header.min.js"></script>
<script src="/frontend-assets/vendor/hs-go-to/dist/hs-go-to.min.js"></script>
<!--<script src="/frontend-assets/vendor/hs-mega-menu/dist/hs-mega-menu.min.js"></script>-->
<script src="/frontend-assets/vendor/typed.js/lib/typed.min.js"></script>
<!--<script src="/frontend-assets/vendor/fancybox/dist/jquery.fancybox.min.js"></script>-->
<script src="/frontend-assets/vendor/aos/dist/aos.js"></script>

<!-- JS Front -->
<script src="/frontend-assets/js/hs.core.js"></script>
<script src="/frontend-assets/js/hs.validation.js"></script>
<!--<script src="../../../../assets/js/hs.fancybox.js"></script>-->

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>-->

{{--<script>
    let app = new Vue({
        el: '#lead-form-section',
        data: {
            form: {
                first: '',
                last: '',
                city: '',
                region: '',
                email: '',
                phone: '',
                utm_source: '',
                utm_medium: '',
                utm_campaign: '',
                locale: 'ar',
            },
            isFormSent: true
        },
        methods: {
            isLeadFormValid() {
                return this.form.first.length &&
                    this.form.last.length &&
                    this.form.city.length &&
                    this.form.region.length &&
                    this.form.phone.length;
            },
            submitLeadForm() {
                this.form.utm_source = this.getUrlParameter('utm_source');
                this.form.utm_medium = this.getUrlParameter('utm_medium');
                this.form.utm_campaign = this.getUrlParameter('utm_campaign');
                let _this = this;
                $.ajax({
                    url: 'https://hook.integromat.com/4iv884yfgda678loxyhv86b9cq34xrv2',
                    type: "post",
                    data: this.form,
                    success: function (response) {
                        _this.isFormSent = true
                        dataLayer.push({
                            'event': 'LeadFromFromLandingVoucher',
                        })
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        _this.isFormSent = true
                    }
                });
            },
            getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                let regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                let results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }
        }
    })
</script>--}}

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // initialization of header
        let header = new HSHeader($('#header')).init();

        // initialization of mega menu
        /*let megaMenu = new HSMegaMenu($('.js-mega-menu'), {
            desktop: {
                position: 'left'
            }
        }).init();*/

        // initialization of text animation (typing)
        let typed = new Typed(".js-text-animation", {
            strings: ["أفضل", "أسرع", "أسهل"],
            typeSpeed: 60,
            loop: true,
            backSpeed: 25,
            backDelay: 1500
        });

        // initialization of fancybox
        /*$('.js-fancybox').each(function () {
            let fancybox = $.HSCore.components.HSFancyBox.init($(this));
        });*/

        // initialization of aos
        AOS.init({
            duration: 650,
            once: true
        });

        // initialization of go to
        $('.js-go-to').each(function () {
            let goTo = new HSGoTo($(this)).init();
        });


        $('#utm_source').val(getUrlParameter('utm_source'));
        $('#utm_medium').val(getUrlParameter('utm_medium'));
        $('#utm_campaign').val(getUrlParameter('utm_campaign'));

        /*let leadBtn = $('#lead-form-button');
        leadBtn.on('click', function (e) {
            alert(212);
            e.preventDefault();
            $('#lead-form-button').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="false"></span>');
            let leadForm = $('#lead-form');

            /!* Get from elements values *!/
            let values = leadForm.serialize();

            $.ajax({
                url: leadForm.attr('data-action-url'),
                type: "post",
                data: values,
                success: function (response) {
                    leadBtn.html('Submit');
                    // You will get response from your PHP page (what you echo or print)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    leadBtn.html('Submit');
                }
            });

        });*/

    });

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };
</script>

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="/frontend-assets/vendor/polifills.js"><\/script>');
</script>

{!! $appPreferences['custom_code_body'] !!}
@stack('scripts')
</body>
</html>
