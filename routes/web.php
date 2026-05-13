<?php

use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('newsletters.index'));

Route::resource('newsletters', NewsletterController::class);
Route::post('newsletters/{newsletter}/start', [NewsletterController::class, 'updateStatus'])
    ->name('newsletters.start');
Route::get('newsletters/{newsletter}/progress', [NewsletterController::class, 'progress'])
    ->name('newsletters.progress');
