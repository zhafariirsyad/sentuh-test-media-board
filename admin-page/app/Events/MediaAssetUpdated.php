<?php

namespace App\Events;

use App\Models\MediaAsset;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MediaAssetUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public MediaAsset $media)
    {}

    public function broadcastOn(): array
    {
        return [new Channel('media-feed')];
    }

    public function broadcastAs(): string
    {
        return 'media.feed.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->media->id,
            'title' => $this->media->title,
            'type' => $this->media->type,
            'url' => $this->media->public_url,
            'mime_type' => $this->media->mime_type,
            'file_size' => $this->media->file_size,
            'updated_at' => $this->media->updated_at?->toIso8601String(),
        ];
    }
}
