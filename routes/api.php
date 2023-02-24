<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('users/list', 'App\Http\Controllers\UserController@listUsers');
Route::resource('users', 'App\Http\Controllers\UserController')->except(['edit', 'create']);
Route::get('groups/list', 'App\Http\Controllers\GroupController@listGroups');
Route::resource('groups', 'App\Http\Controllers\GroupController')->except(['edit', 'create']);  