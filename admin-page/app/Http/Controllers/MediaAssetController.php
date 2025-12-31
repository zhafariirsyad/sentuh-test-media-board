<?php

namespace App\Http\Controllers;

use App\Services\MediaAssetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function __construct(
        private readonly MediaAssetService $service,
    ) {}

    public function index(): View
    {
        $media = $this->service->current();

        return view('media.index', compact('media'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/gif,video/mp4', 'max:51200'],
        ]);

        $this->service->updateFromUpload(
            $validated['title'] ?? null,
            $validated['file'],
        );

        return back()->with('status', 'Media updated successfully.');
    }
}
