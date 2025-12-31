<?php

namespace App\Repositories;

use App\Models\MediaAsset;

class MediaAssetRepository
{
    public function latest(): MediaAsset|null
    {
        return MediaAsset::query()->latest('updated_at')->first();
    }
}
