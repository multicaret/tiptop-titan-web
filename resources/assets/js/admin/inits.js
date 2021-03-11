const primaryColor = '#09464d';
const dangerColor = '#d0021b';

window.toast = swal.mixin({
    toast: true,
    position: window.App.dir == 'rtl' ? 'top-start' : 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }
});


$(function () {

    /*Theme Layout related */
    // Auto update layout
    (function () {
        window.layoutHelpers.setAutoUpdate(true);
    })();

    // Collapse menu
    (function () {
        if ($('#layout-sidenav').hasClass('sidenav-horizontal') || window.layoutHelpers.isSmallScreen()) {
            return;
        }

        try {
            window.layoutHelpers.setCollapsed(
                localStorage.getItem('layoutCollapsed') === 'true',
                false
            );
        } catch (e) {
        }
    })();

    $(function () {
        // Initialize sidenav
        $('#layout-sidenav').each(function () {
            new SideNav(this, {
                orientation: $(this).hasClass('sidenav-horizontal') ? 'horizontal' : 'vertical'
            });
        });

        // Initialize sidenav togglers
        $('body').on('click', '.layout-sidenav-toggle', function (e) {
            e.preventDefault();
            window.layoutHelpers.toggleCollapsed();
            if (!window.layoutHelpers.isSmallScreen()) {
                try {
                    localStorage.setItem('layoutCollapsed', String(window.layoutHelpers.isCollapsed()));
                } catch (e) {
                }
            }
        });

        if ($('html').attr('dir') === 'rtl') {
            $('#layout-navbar .dropdown-menu').toggleClass('dropdown-menu-right');
        }
    });


    /* Select 2 */
    let select2MainOptions = {
        // theme: 'bootstrap',
        dir: window.App.dir,
        rtl: (window.App.dir === 'rtl'),
        // maximumSelectionLength: 100,
        escapeMarkup: function (markup) {
            return markup;
        }
    };
    const select2MainOptionsWithAjax = Object.assign({
        minimumInputLength: 2,
        ajax: {
            dataType: 'json',
            delay: 250,
            method: 'POST',
            data: function (params) {
                return {
                    q: params.term,
                };
            },
            processResults: function (data) {
                lastResults = data;
                return {
                    results: data
                };
            },
            cache: true
        },
        createTag: function (params) {
            term = $.trim(params.term);
            if (term === '') return null;
            if (!lastResults.some(function (r) {
                return r.title == term;
            })) {
                return {
                    id: term,
                    text: term + ' *'
                };
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        templateResult: function (repo) {
            if (repo.loading) return repo;
            let name = repo.name || repo.text || repo.title || repo.username;
            if (name != undefined) {
                return '<li>' + name + '</li>';
            }
        },
        templateSelection: function (repo) {
            return repo.name || repo.text || repo.title || repo.username;
        },
    }, select2MainOptions);

    $('[data-select-two]').select2(select2MainOptions);
    $('[data-select-two][data-ajax--url]').select2(select2MainOptionsWithAjax);
    $('[data-select-two="tags-only"]').select2(Object.assign({tags: true}, select2MainOptions));
    $('[data-select-two="tags-only"][data-ajax--url]').select2(Object.assign({tags: true}, select2MainOptionsWithAjax));
    $('[data-select-two="multiple"]').select2(Object.assign({multiple: true}, select2MainOptions));
    $('[data-select-two="multiple"][data-ajax--url]').select2(Object.assign({multiple: true}, select2MainOptionsWithAjax));
    $('[data-select-two="tags"]').select2(Object.assign({tags: true, multiple: true}, select2MainOptions));
    $('[data-select-two="tags"][data-ajax--url]').select2(Object.assign({
        tags: true,
        multiple: true
    }, select2MainOptionsWithAjax));


    /* Deletion Helpers */
    $(document).on('click', '[data-delete]', function (e) {
        e.preventDefault();
        let _this = $(this);
        let form = _this.parent().find('form');
        swal.fire({
            title: window.App.translations.delete_confirmation_title,
            text: window.App.translations.delete_confirmation_message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: primaryColor,
            cancelButtonColor: dangerColor,
            confirmButtonText: window.App.translations.delete_confirmation_btn,
            cancelButtonText: window.App.translations.cancel_btn_text
        }).then(function (result) {
            if (result.value) {
                form.submit();
            }
        });
    });

    // to add new logo when sidnav collapsed
    let sideNavLogo = $('.side-nav-logo');
    let layoutSidenav = $('#layout-sidenav');

    $('body').on('click', '.layout-sidenav-toggle', function (e) {
        if (!window.layoutHelpers.isCollapsed()) {
            sideNavLogo.attr('src', '/images/logo.png');
            sideNavLogo.attr('width', '50px');
        } else {
            sideNavLogo.attr('src', '/images/logo-horizontal.png');
            sideNavLogo.attr('width', '160px');
        }
    });

    layoutSidenav.mouseover(function (e) {
        if ($('#layout').hasClass('layout-collapsed')) {
            sideNavLogo.attr('src', '/images/logo-horizontal.png');
            sideNavLogo.attr('width', '160px');
        }
    })

    layoutSidenav.mouseout(function (e) {
        if ($('#layout').hasClass('layout-collapsed')) {
            sideNavLogo.attr('src', '/images/logo.png');
            sideNavLogo.attr('width', '50px');
        }
    })

});
