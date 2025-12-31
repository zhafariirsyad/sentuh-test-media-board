<?php

namespace App\Http\Controllers;

use App\Services\MediaFeedService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MediaFeedController extends Controller
{
    protected MediaFeedService $mediaFeedService;
    public function __construct(MediaFeedService $mediaFeedService) {
        $this->mediaFeedService = $mediaFeedService;
    }

    public function index(): View
    {
        $media = $this->mediaFeedService->latest();

        return view('media.feed', compact('media'));
    }

    public function latest(): JsonResponse
    {
        $media = $this->mediaFeedService->latest();

        return response()->json([
            'data' => $media,
        ]);
    }
}
