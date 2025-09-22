<?php

use Illuminate\Support\Facades\Route;

Route::prefix('memos')->as('memos.')->group(function(){
    Route::post('/', \App\Http\Controllers\CreateController::class)->name('create');
    Route::get('/', \App\Http\Controllers\ListController::class)->name('list');
});
