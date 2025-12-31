const root = document.getElementById('media-root');

if (root) {
    const display = document.getElementById('media-display');
    const titleEl = document.getElementById('media-title');
    const metaEl = document.getElementById('media-meta');
    const updatedTextEl = document.getElementById('last-update-text');
    const statusEl = document.getElementById('connection-status');

    const parseInitialMedia = () => {
        try {
            return JSON.parse(root.dataset.initialMedia ?? 'null');
        } catch {
            return null;
        }
    };

    let currentMedia = parseInitialMedia();

    const setStatus = (text, colorClass) => {
        if (!statusEl) {
            return;
        }

        statusEl.textContent = text;
        statusEl.className = colorClass;
    };

    const formatMeta = (media) => {
        if (!media) {
            return 'Menunggu data terbaru dari admin.';
        }

        const sizeKb = media.file_size ? (media.file_size / 1024).toFixed(1) : '0.0';

        return `${media.type?.toUpperCase() ?? 'MEDIA'} · ${media.mime_type ?? 'unknown'} · ${sizeKb} KB`;
    };

    const renderMedia = (media) => {
        if (!display) {
            return;
        }

        display.innerHTML = '';

        if (!media) {
            const placeholder = document.createElement('p');
            placeholder.id = 'media-placeholder';
            placeholder.className = 'text-slate-500 text-lg';
            placeholder.textContent = 'Belum ada media.';
            display.appendChild(placeholder);
            return;
        }

        if (media.type === 'video') {
            const video = document.createElement('video');
            video.id = 'media-element';
            video.src = media.url ?? media.public_url;
            video.autoplay = true;
            video.loop = true;
            video.muted = true;
            video.playsInline = true;
            video.controls = true;
            video.className = 'w-full h-full object-cover';
            display.appendChild(video);
            return;
        }

        const img = document.createElement('img');
        img.id = 'media-element';
        img.src = media.url ?? media.public_url;
        img.alt = media.title ?? 'Media terkini';
        img.className = 'w-full h-full object-contain';
        display.appendChild(img);
    };

    const applyMedia = (media) => {
        currentMedia = media;
        renderMedia(media);

        if (titleEl) {
            titleEl.textContent = media?.title ?? '—';
        }

        if (metaEl) {
            metaEl.textContent = formatMeta(media);
        }

        if (updatedTextEl) {
            if (media?.updated_at) {
                updatedTextEl.textContent = `terakhir diperbarui ${new Date(media.updated_at).toLocaleString()}`;
            } else {
                updatedTextEl.textContent = 'menunggu unggahan pertama';
            }
        }
    };

    const fetchLatest = async () => {
        try {
            const response = await window.axios.get('/api/media/latest');
            if (response.data?.data) {
                applyMedia(response.data.data);
            }
        } catch (error) {
            console.error('Gagal memuat media terbaru', error);
            setStatus('gagal memuat data awal', 'text-red-400');
        }
    };

    const wireConnectionLogging = () => {
        const connector = window.Echo?.connector;
        const pusher = connector?.pusher;
        const connection = pusher?.connection;

        if (!connection) {
            console.warn('[MediaFeed] Tidak menemukan koneksi Echo/Pusher');
            return;
        }

        connection.bind('connected', () => {
            console.info('[MediaFeed] Echo connected');
            setStatus('terhubung', 'text-green-400');
        });

        connection.bind('error', (error) => {
            console.error('[MediaFeed] Echo error', error);
            setStatus('realtime error', 'text-red-400');
        });

        connection.bind('disconnected', () => {
            console.warn('[MediaFeed] Echo disconnected');
            setStatus('terputus', 'text-red-400');
        });
    };

    const subscribeChannel = () => {
        if (!window.Echo) {
            console.warn('[MediaFeed] Echo belum siap');
            setStatus('realtime tidak tersedia', 'text-red-400');
            return;
        }

        setStatus('menunggu update…', 'text-yellow-400');
        console.info('[MediaFeed] subscribing to media-feed');

        window.Echo.channel('media-feed')
            .listen('.media.feed.updated', (event) => {
                console.info('[MediaFeed] update diterima', event);
                setStatus('update diterima', 'text-green-400');
                applyMedia(event);
            })
            .error((error) => {
                console.error('[MediaFeed] error channel media-feed', error);
                setStatus('gagal langganan realtime', 'text-red-400');
            });
    };

    applyMedia(currentMedia);
    wireConnectionLogging();
    subscribeChannel();
    fetchLatest();
}
