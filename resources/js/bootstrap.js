import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// import Echo from 'laravel-echo';
 
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
 
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     wsHost: import.meta.env.VITE_PUSHER_HOST,
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true,
//     enabledTransports: ['ws', 'wss'],
// });