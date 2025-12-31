<?php

namespace App\Repositories;

use App\Models\MediaAsset;

class MediaAssetRepository
{
    public function latest(): MediaAsset|null
    {
        return MediaAsset::query()->latest('updated_at')->first();
    }

    public function save(MediaAsset $media, array $attributes): MediaAsset
    {
        $media->fill($attributes);
        $media->save();

        return $media->fresh();
    }
}
