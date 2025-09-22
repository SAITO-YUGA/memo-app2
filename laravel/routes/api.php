<?php

use Illuminate\Support\Facades\Route;

Route::prefix('memo')->as('memo.')->group(function(){
    Route::post('/', \App\Http\Controllers\CreateController::class)->name('create');
});
