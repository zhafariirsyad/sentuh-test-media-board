<?php

use App\Http\Controllers\MediaAssetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MediaAssetController::class, 'index'])->name('media.index');
Route::post('/media', [MediaAssetController::class, 'store'])->name('media.store');
