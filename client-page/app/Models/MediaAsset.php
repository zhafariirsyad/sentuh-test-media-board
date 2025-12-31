<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaAsset extends Model
{
    protected $fillable = [
        'title',
        'type',
        'storage_path',
        'mime_type',
        'file_size',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'public_url',
    ];

    public function getPublicUrlAttribute(): string
    {
        $baseUrl = rtrim(config('services.media.base_url', config('app.url')), '/');

        return $baseUrl.'/storage/'.ltrim($this->storage_path, '/');
    }
}
