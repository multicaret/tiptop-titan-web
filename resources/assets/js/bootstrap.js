/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */
import $ from 'jquery';

try {
    window.$ = window.jQuery = $;
    require('bootstrap');
    require('owl.carousel');
    window.swal = require('sweetalert2');
    require('select2');
} catch (e) {
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
window.saveAdminThemeSettings = function (key, value) {
    let url = window.App.domain + `/en/ajax/theme-settings/save`;
    axios.post(url, {key, value});
};

function loadAdminThemeSettings() {
    const isLoaded = localStorage.getItem('isLoaded');
    if (!isLoaded || !Boolean(isLoaded)) {
        let url = window.App.domain + `/en/ajax/theme-settings/load`;
        axios.get(url).then((res) => {
            if (res.data.length > 0) {
                for (let i = 0; i < res.data.length; i++) {
                    const tempItem = res.data[i];
                    let tempKey = (String(tempItem.key).split('-'))[0];
                    localStorage.setItem("themeSettings".concat(tempKey), String(tempItem.value));
                }
                localStorage.setItem("isLoaded", String(true));
                window.location.reload();
            }
        });
    }
}

loadAdminThemeSettings();
