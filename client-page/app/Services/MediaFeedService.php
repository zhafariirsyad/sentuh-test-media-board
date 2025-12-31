<?php

namespace App\Services;

use App\Repositories\MediaAssetRepository;
use App\Models\MediaAsset;

class MediaFeedService
{
    public function __construct(
        private readonly MediaAssetRepository $repository,
    ) {}

    public function latest(): MediaAsset|null
    {
        return $this->repository->latest();
    }
}
