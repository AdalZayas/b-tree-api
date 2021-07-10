<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/v1/b-trees/height', 'App\Http\Controllers\BTreeController@heigth');
Route::post('/v1/b-trees/neighbors', 'App\Http\Controllers\BTreeController@neighbors');
Route::post('/v1/b-trees/bfs', 'App\Http\Controllers\BTreeController@bfs');