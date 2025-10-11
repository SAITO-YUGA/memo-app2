<?php

use Illuminate\Support\Facades\Route;

Route::prefix('memos')->as('memos.')->group(function(){
    Route::post('/', \App\Http\Controllers\CreateController::class)->name('create');
    Route::get('/', \App\Http\Controllers\ListController::class)->name('list');
    Route::delete('{id}',\App\Http\Controllers\DeleteController::class)->name('delete');
    Route::patch('{id}', \App\Http\Controllers\UpdateController::class)->name('update');
    Route::patch('{memo}/favorite',\App\Http\Controllers\FavoriteController::class)->name('favorite.toggle');
});
