<?php

namespace App\Services;

use App\Events\MediaAssetUpdated;
use App\Models\MediaAsset;
use App\Repositories\MediaAssetRepository;
use Illuminate\Http\UploadedFile;

class MediaAssetService
{
    protected MediaAssetRepository $mediaAssetRepository;
    public function __construct(MediaAssetRepository $mediaAssetRepository) {
        $this->mediaAssetRepository = $mediaAssetRepository;
    }

    public function current(): MediaAsset|null
    {
        return $this->mediaAssetRepository->latest();
    }

    public function updateFromUpload(?string $title, UploadedFile $file): MediaAsset
    {
        $path = $file->store('media', 'public');
        $mimeType = $file->getMimeType();
        $type = str_starts_with($mimeType, 'image/') ? 'image' : 'video';

        $attributes = [
            'title' => $title,
            'type' => $type,
            'storage_path' => $path,
            'mime_type' => $mimeType,
            'file_size' => $file->getSize(),
        ];

        $existing = $this->mediaAssetRepository->latest();

        if (! $existing) {
            $existing = new MediaAsset([
                'published_at' => now(),
            ]);
        }

        $media = $this->mediaAssetRepository->save($existing, $attributes);

        MediaAssetUpdated::dispatch($media);

        return $media;
    }
}
