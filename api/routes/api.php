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

Route::get('nested-set', 'App\Http\Controllers\Api\NestedSetController@getCollection');
Route::get('nested-set/{id}', 'App\Http\Controllers\Api\NestedSetController@getItem');
Route::post('nested-set', 'App\Http\Controllers\Api\NestedSetController@createItem');
Route::put('nested-set/{id}', 'App\Http\Controllers\Api\NestedSetController@updateItem');
Route::delete('nested-set/{id}','App\Http\Controllers\Api\NestedSetController@deleteItem');
