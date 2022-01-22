<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ModelController;
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
Route::get('/search', [HomeController::class, 'search']);

Route::get('/school/{idOrShortName}', [SchoolController::class, 'school']);
Route::post('/fetch', [SchoolController::class, 'fetch']);

Auth::routes();

Route::get('/admin', [AdminController::class, 'index']);

Route::get('/admin/import', [ImportController::class, 'get']);
Route::post('/admin/import', [ImportController::class, 'post']);

Route::get('/admin/m/{model}', [ModelController::class, 'index']);
Route::post('/admin/r/list', [ModelController::class, 'list']);

