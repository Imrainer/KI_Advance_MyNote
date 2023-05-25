<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WEB\UserControllers;
use App\Http\Controllers\WEB\AuthControllers;
use App\Http\Controllers\WEB\AdminControllers;
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

Route::get('/', [AuthControllers::class, 'logpage']);
Route::post('/login', [AuthControllers::class, 'login']);
Route::get('/logout', [AuthControllers::class, 'logout']);


Route::get('/dashboard', [UserControllers::class, 'index']);
Route::get('/admin', [AdminControllers::class, 'index']);