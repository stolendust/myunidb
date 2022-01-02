<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchoolController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);
Route::post('/search', [HomeController::class, 'search']);

Route::post('/school/search', [SchoolController::class, 'search']);
Route::post('/school/fetch', [SchoolController::class, 'fetch']);
Route::get('/school/index/{shortname}', [SchoolController::class, 'index']);

