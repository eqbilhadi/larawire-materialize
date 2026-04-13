<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::view('/', 'welcome')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/stream/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('stream.file');

Route::post('/lang', function (Request $request) {
    $request->validate([
        'locale' => 'required|in:en,tl,pt',
    ]);

    $request->session()->put('locale', $request->locale);

    $request->session()->save();

    return back();
})->name('lang.switch');

require __DIR__.'/auth.php';
