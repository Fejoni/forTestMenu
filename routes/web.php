<?php

\Illuminate\Support\Facades\Route::get('/', function () {
});

Route::get('/generate-menu',   function () {return view('generate');});
