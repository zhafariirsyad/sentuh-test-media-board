import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const scheme = import.meta.env.VITE_REVERB_SCHEME ?? 'http';
const wsHost = import.meta.env.VITE_REVERB_HOST ?? window.location.hostname;
const wsPort = Number(import.meta.env.VITE_REVERB_PORT ?? 8080);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost,
    wsPort,
    wssPort: wsPort,
    forceTLS: scheme === 'https',
    encrypted: false,
    enabledTransports: scheme === 'https' ? ['wss'] : ['ws'],
});
