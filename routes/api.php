<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthControllers;
use App\Http\Controllers\API\UserControllers;
use App\Http\Controllers\API\NoteControllers;
use App\Http\Controllers\API\CategoryControllers;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// <!--AUTH--!>

Route::prefix('auth')->group(function () {
    Route::post('/login',[AuthControllers::class, 'login']);
    Route::post('/logout', [AuthControllers::class, 'logout']);
    Route::get('/me', [AuthControllers::class, 'getUserByToken']);
    Route::post('/register', [AuthControllers::class, 'register']);
});

// <!--USER--!>

Route::prefix('user')->group(function () {
    Route::post('/edit-profile',[UserControllers::class, 'edit']);
    Route::post('/edit-password',[UserControllers::class, 'editPassword']);
});

// <!--NOTE---!>

Route::prefix('note')->group(function () {
    Route::post('/create',[NoteControllers::class, 'create']);
    Route::get('/index',[NoteControllers::class, 'index']);
    Route::get('/{uuid}',[NoteControllers::class, 'byId']);
    Route::post('/edit/{uuid}',[NoteControllers::class, 'edit']);
    Route::post('/favorite/{uuid}',[NoteControllers::class, 'favorite']);
    Route::post('/unfavorite/{uuid}',[NoteControllers::class, 'unfavorite']);
    Route::delete('/delete/{uuid}',[NoteControllers::class,'delete']);
});

// <!--CATEGORY---!>

Route::prefix('category')->group(function () {
    Route::post('/create', [CategoryControllers::class, 'create']);
    Route::get('/index', [CategoryControllers::class, 'read']);
    Route::get('/{uuid}',[CategoryControllers::class, 'byId']);
    Route::post('/edit/{uuid}',[CategoryControllers::class, 'edit'])->name('edit_category');
    Route::delete('/delete/{uuid}', [CategoryControllers::class, 'delete'])->name('delete_category');
});