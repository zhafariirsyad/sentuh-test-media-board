<?php

use App\Http\Controllers\MediaFeedController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MediaFeedController::class, 'index'])->name('media.index');
Route::get('/api/media/latest', [MediaFeedController::class, 'latest'])->name('media.latest');
