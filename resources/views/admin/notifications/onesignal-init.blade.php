<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

{{--<script type="text/javascript">
    if (window.App.authenticated) {

    }
</script>--}}
<script>
    let OneSignal = window.OneSignal || [];
    OneSignal.push(function () {
        OneSignal.init({
            appId: "{{config('services.onesignal.app_id')}}",
            notifyButton: {
                enable: true,
            },
            persistNotification: true,
            welcomeNotification: {
                "title": "Welcome 😇",
                "message": "Thanks for subscribing!",
                // "url": "" /* Leave commented for the notification to not open a window on Chrome and Firefox (on Safari, it opens to your webpage) */
            }
        });
        OneSignal.showNativePrompt();
    });


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
                /* Subscribed, opt them out */
                // console.log("Subscribed, opt them out");
                // OneSignal.setSubscription(false);
            } else {
                if (state.isOptedOut) {
                    /* Opted out, opt them back in */
                    console.log("Opted out, opt them back in");
                    OneSignal.setSubscription(true);
                } else {
                    /* Unsubscribed, subscribe them */
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
            /* If the user's subscription state changes during the page's session, update the button text */
            updateMangeWebPushSubscription();
        });
    });
</script>
