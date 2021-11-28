<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

Route::post('/user/register', 'UserController@register');
Route::post('/user/auth', 'UserController@auth');
Route::put('/user/settings', 'UserController@settings')->middleware(CheckToken::class);
