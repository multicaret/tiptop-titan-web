<script>

    let notifications_list = [];

    function getNotificationsList(clear_new = true) {
        setTimeout(() => {
            if ($('#notification_list_li').hasClass('m-dropdown--open') || notifications_list.length == 0) {
                $('#notification_list').empty();
                /*mApp.block("#notifications_block", {
                    overlayColor: "#ffffff",
                    type: "loader",
                    state: "success",
                    message: "يتم التحميل .."
                });*/
                /*$.ajax({
                    url: "route('firebase::notifications')",
                    type: "GET",
                    dataType: "json",
                    data: {
                        clear_new: clear_new
                    },
                    beforeSend: function () {
                        $('#notifications_count').html(`<div class="m-spinner m-spinner--danger m-spinner--sm"></div>`);
                    },
                    success: function (data) {
                        mApp.unblock("#notifications_block");
                        $('#notifications_count').html(data.new_count + ' جديد').hide().fadeIn();
                        if (data.new_count > 0 && notifications_list.length == 0) {
                            $('#new_notifications_badge').show();
                            $('.notification_bell').addClass('animated');
                        } else {
                            $('#new_notifications_badge').hide();
                            $('.notification_bell').removeClass('animated');
                        }
                        notifications_list = data.notifications;
                        $.each(data.notifications, function (key, value) {
                            if (value.seen) {
                                $('#notification_list').append(`
                                <div class="m-list-timeline__item m-list-timeline__item--read">
                                    <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                    <a href="${value.link}" class="m-list-timeline__text">${value.title}</a>
                                    <span class="m-list-timeline__time">${value.time_ago}</span>
                                </div>
                                `).hide().fadeIn();
                            } else {
                                $('#notification_list').append(`
                                <div class="m-list-timeline__item">
                                    <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                    <a href="${value.link}" class="m-list-timeline__text text-primary">${value.title}</a>
                                    <span class="m-list-timeline__time text-primary">${value.time_ago}</span>
                                </div>
                                `).hide().fadeIn();
                            }
                        });
                    }
                });*/
            }
        }, 50);
    }

    getNotificationsList(false);
    $('#m_topbar_notification_icon').click(function () {
        getNotificationsList();
    });
</script>
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

<script type="text/javascript">


    /*let sendAjax = function (token) {
        console.log(token)
        $.ajax({
            type: "POST",
            url: 'route('firebase::set_one_signal_token')',
            data: {
                desktop_token: token,
                _token: "{{ csrf_token() }}",
            },
            success: function (data) {
                // console.log(data);
            }
        });
    }*/

    let showNotification = function (content) {
        $('#new_notifications_badge').show();
        $('.notification_bell').addClass('animated');
        $.notify(content, {
            type: content.type,
            allow_dismiss: true,
            newest_on_top: true,
            mouse_over: true,
            spacing: 10,
            timer: 5000,
            placement: {
                from: 'top',
                align: 'center'
            },
            offset: {
                x: 30,
                y: 30
            },
            delay: 1000,
            z_index: 10000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            }
        });
    }
    @if(auth()->check())
    let useragentid = null;
    let OneSignal = window.OneSignal || [];
    OneSignal.push(["init", {
        appId: "{{config('services.onesignal.app_id')}}",
        // safari_web_id: "web.onesignal.auto.50aab334-fdef-4f2b-a12a-95c1e616813d",
        autoResubscribe: true,
        autoRegister: false,
        notifyButton: {
            enable: true
        },
        persistNotification: true,
        welcomeNotification: {
            "title": "",
            "message": "TEST",
        }

    }]);

    OneSignal.push(function () {
        OneSignal.showNativePrompt();
        OneSignal.setDefaultNotificationUrl('{{url('/')}}');
        OneSignal.setDefaultTitle("TipTop Team");
    });

    //Firstly this will check user id
    OneSignal.push(function () {
        OneSignal.showNativePrompt();

        OneSignal.on('notificationPermissionChange', function (permissionChange) {
            let currentPermission = permissionChange.to;
            console.log('New permission state:', currentPermission);
        });
        OneSignal.getUserId().then(function (userId) {
            if (userId != null) {
                console.log('OneSignal user id: ' + userId)
                useragentid = userId;
                OneSignal.push(["getNotificationPermission", function (permission) {


                    console.log('getNotificationPermission  ' + permission)

                    if (permission == 'granted') {

                        OneSignal.isPushNotificationsEnabled(function (isEnabled) {
                            if (isEnabled) {
                                // sendAjax(userId)
                                OneSignal.push(function () {
                                    OneSignal.setExternalUserId({{auth()->user()->id}});
                                });
                                @if($auth)
                                OneSignal.push(function () {
                                    OneSignal.sendTags({
                                        role: '{{$auth->role_name}}',

                                    }).then(function (tagsSent) {
                                        console.log('tags are sent' + tagsSent)
                                    });
                                });
                                @endif
                            }
                        });

                    }
                }]);

            }
        });
    });


    //Secondly this will check when subscription changed
    OneSignal.push(function () {
        OneSignal.on('subscriptionChange', function (isSubscribed) {
            if (isSubscribed == true) {
                OneSignal.getUserId().then(function (userId) {
                    useragentid = userId;
                }).then(function () {
                    console.log('User Subscribed: ' + useragentid)
                    OneSignal.setSubscription(true);
                    // sendAjax(useragentid)
                    OneSignal.push(function () {
                        OneSignal.setExternalUserId({{auth()->user()->id}});
                    });
                    @if($auth)
                    OneSignal.push(function () {
                        OneSignal.sendTags({
                            role: '{{$auth->role_name}}',

                        }).then(function (tagsSent) {
                            console.log('tags are sent' + tagsSent);
                        });
                    });
                    @endif
                });

            } else if (isSubscribed == false) {
                OneSignal.setSubscription(false);
                OneSignal.removeExternalUserId();
            } else {
                console.log('Unable to process the request');
            }
        });
    });

    OneSignal.push(function () {
        OneSignal.on('notificationDisplay', function (event) {
            console.log('notificationDisplay OneSignal', event);
            showNotification({
                title: event.heading,
                message: event.content,
                icon: 'flaticon-alarm-1',
                url: event.url,
                target: '_blank',
                type: 'info'
            });

            let audio = new Audio('https://cdn.jsdelivr.net/npm/ion-sound@3.0.7/sounds/bell_ring.mp3');
            audio.play();
        });
    });

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/OneSignalSDKWorker.js')
            .then(function () {
                console.log('Service worker registered');
            })
            .catch(function (error) {
                console.log('Service worker registration failed:', error);
            });
    } else {
        console.log('Service workers are not supported.');
    }





    @endif

</script>

