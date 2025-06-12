<?php

\Illuminate\Support\Facades\Route::get('/', function () {
});

Route::get('/generate-menu',   function () {return view('generate');});
Route::post('/generate-menu', [ \App\Http\Controllers\Api\v1\Admin\MenuGenerateController::class, 'generateMenu'])->name('generate-menu');
