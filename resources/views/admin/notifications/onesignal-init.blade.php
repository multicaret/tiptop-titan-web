<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

<script>
    window.OneSignal = window.OneSignal || [];
    OneSignal.push(function () {
        OneSignal.init({
            appId: "{{config('services.onesignal.dashboard_app_id')}}",
            notifyButton: {
                enable: true,
            },
            subdomainName: "titan-tiptop",
            persistNotification: true,
            welcomeNotification: {
                "title": "Welcome 😇",
                "message": "Thanks for subscribing!",
                // "url": "" /* Leave commented for the notification to not open a window on Chrome and Firefox (on Safari, it opens to your webpage) */
            },
            autoRegister: true,
            notificationClickHandlerMatch: 'origin',
            notificationClickHandlerAction: 'focus',
        });
        // OneSignal.showNativePrompt();

        OneSignal.on('notificationPermissionChange', (permissionChange) => {
            console.log('permissionChange.to', permissionChange.to);
            const currentPermission = permissionChange.to;
            console.log('New permission state:', currentPermission); // true , false
        });

        OneSignal.registerForPushNotifications();


        OneSignal.on('subscriptionChange', (isSubscribed) => {
            console.log('The user\'s subscription state is now:', isSubscribed);
            // this.isSubscribed = isSubscribed;
        });
        OneSignal.on('notificationDisplay', (event) => {
            console.log('OneSignal notification displayed:', event);
        });
        OneSignal.on('notificationDismiss', (event) => {
            console.log('OneSignal notification dismiss:', event);
        });
        OneSignal.getUserId().then((userId) => {
            // console.log('User ID is', userId);
            // this.oneSignalId = userId;
            // this.updateLocalUserProfile();
        }).catch(err => {
            console.error('err', err);
        });
    });


    OneSignal.push(['addListenerForNotificationOpened', (data) => {
        console.log('Received NotificationOpened:');
        console.log(data);
    }]);


    // Check if subscribed
    OneSignal.push(() => {
        OneSignal.isPushNotificationsEnabled((isEnabled) => {
            if (isEnabled) {
                console.log('Push notifications are enabled!');
                if (window.App.authenticated) {
                    OneSignal.setExternalUserId(window.App.userId);
                }
            } else {
                console.log('Push notifications are not enabled yet.');
                // this.subscribe();
            }
        }, (error) => {
            console.log('Push permission not granted');
        });
    });

    /*
        // Example
        function getSubscriptionState() {
            return Promise.all([
                OneSignal.isPushNotificationsEnabled(),
                OneSignal.isOptedOut()
            ]).then(function (result) {
                let isPushEnabled = result[0];
                let isOptedOut = result[1];

                return {
                    isPushEnabled: isPushEnabled,
                    isOptedOut: isOptedOut
                };
            });
        }

        function updateMangeWebPushSubscription() {
            getSubscriptionState().then(function (state) {
                if (state.isPushEnabled) {
                    /!* Subscribed, opt them out *!/
                    // console.log("Subscribed, opt them out");
                    // OneSignal.setSubscription(false);
                } else {
                    if (state.isOptedOut) {
                        /!* Opted out, opt them back in *!/
                        console.log("Opted out, opt them back in");
                        OneSignal.setSubscription(true);
                    } else {
                        /!* Unsubscribed, subscribe them *!/
                        console.log("Unsubscribed, subscribe them");
                        OneSignal.registerForPushNotifications();
                    }
                }
            });
        }


        OneSignal.push(function () {
            // If we're on an unsupported browser, do nothing
            if (!OneSignal.isPushNotificationsSupported()) {
                return;
            }
            updateMangeWebPushSubscription();
            OneSignal.on("subscriptionChange", function (isSubscribed) {
                /!* If the user's subscription state changes during the page's session, update the button text *!/
                updateMangeWebPushSubscription();
            });
        });*/
</script>
