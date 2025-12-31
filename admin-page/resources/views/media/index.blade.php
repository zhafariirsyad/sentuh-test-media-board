<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Media Upload</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <main class="max-w-3xl mx-auto py-12 px-4">
        <h1 class="text-3xl font-semibold mb-6">Media Admin</h1>

        @if (session('status'))
            <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 shadow rounded">
            @csrf
            <div>
                <label class="block text-sm font-medium">Judul (opsional)</label>
                <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full rounded border-gray-300 shadow-sm">
                @error('title')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">File Gambar / Video</label>
                <input type="file" name="file" accept="image/*,video/*" class="mt-1 w-full rounded border-gray-300 shadow-sm">
                @error('file')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700">Upload</button>
        </form>

        <section class="mt-8 bg-white p-6 shadow rounded">
            <h2 class="text-xl font-semibold mb-4">Preview Saat Ini</h2>
            @if ($media)
                <p class="text-sm text-gray-500 mb-4">
                    Terakhir diupdate {{ $media->updated_at?->diffForHumans() }} | {{ strtoupper($media->type) }}
                </p>
                @if ($media->type === 'image')
                    <img src="{{ $media->public_url }}" alt="{{ $media->title }}" class="w-full rounded">
                @else
                    <video src="{{ $media->public_url }}" controls class="w-full rounded"></video>
                @endif
            @else
                <p class="text-sm text-gray-500">Belum ada media yang diupload.</p>
            @endif
        </section>
    </main>
</body>
</html>
