<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/convert/{pattern}/{serial}', 'SerialConverter@processOutput'); //->where('pattern', '[A-Z]+');

Route::get('/{name?}', function ($name = 'John') {
	return $name;
});