<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Display</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white min-h-screen">
    <main class="max-w-4xl mx-auto px-4 py-12 space-y-8" id="media-root" data-initial-media='@json($media)'>
        <header class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-wide text-slate-400">Realtime Display</p>
                <h1 class="text-3xl font-semibold">Sentuh Media Board</h1>
            </div>
            <div class="text-right text-sm">
                <p id="last-update-text" class="text-slate-400">
                    @if ($media)
                        terakhir diperbarui {{ $media->updated_at?->diffForHumans() }}
                    @else
                        menunggu unggahan pertama
                    @endif
                </p>
                <p id="connection-status" class="text-green-400">terhubung</p>
            </div>
        </header>

        <section id="media-display" class="aspect-video w-full bg-slate-900 flex items-center justify-center rounded-xl shadow-xl overflow-hidden">
            @if ($media)
                @if ($media->type === 'image')
                    <img src="{{ $media->public_url }}" alt="{{ $media->title }}" class="w-full h-full object-contain" id="media-element">
                @else
                    <video src="{{ $media->public_url }}" autoplay muted loop playsinline controls class="w-full h-full object-cover" id="media-element"></video>
                @endif
            @else
                <p class="text-slate-500 text-lg" id="media-placeholder">Belum ada media.</p>
            @endif
        </section>

        <div class="space-y-2">
            <p class="text-lg font-medium" id="media-title">{{ $media?->title ?? '—' }}</p>
            <p class="text-sm text-slate-400" id="media-meta">
                @if ($media)
                    {{ strtoupper($media->type) }} · {{ $media->mime_type }} · {{ number_format(($media->file_size ?? 0) / 1024, 1) }} KB
                @else
                    Menunggu data terbaru dari admin.
                @endif
            </p>
        </div>
    </main>
</body>
</html>
