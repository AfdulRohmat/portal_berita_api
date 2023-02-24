<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthenticationContoller;

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

// SANCTUM AUTHENTICATION UNTUK LOGIN
Route::middleware(['auth:sanctum'])->group(function () {
// GET DATA USER
Route::get('/user', [AuthenticationContoller::class, 'getDataUser']);

//LOGOUT
Route::get('/logout', [AuthenticationContoller::class, 'logout']);

// CREATE POST
Route::post('/post', [PostController::class, 'store']);

// UPDATE POST // // MIDDLEWARE UNTUK MEMASTIKAN YANG BISA UPDATE POST HANYA USER SI PEMBUAT
Route::patch('/post/{id}',  [PostController::class, 'update'])->middleware('pemilik-postingan');

// DELETE POST
Route::delete('/post/{id}',  [PostController::class, 'destroy'])->middleware('pemilik-postingan');

// CREATE COMMENT
Route::post('/comment', [CommentController::class, 'store']);

// UPDATE COMMENT
Route::patch('/comment/{id}',  [CommentController::class, 'update'])->middleware('pemilik-komentar');

// DELETE COMMENT
Route::delete('/comment/{id}',  [CommentController::class, 'destroy'])->middleware('pemilik-komentar');
});

// GET DATA POSTS
Route::get('/posts', [PostController::class, 'index']);

// GET DETAIL POST BY ID
Route::get('/post/{id}', [PostController::class, 'show']);


// REGISTER NEW USER
Route::post('/register', [AuthenticationContoller::class, 'register']);

// LOGIN
Route::post('/login', [AuthenticationContoller::class, 'login']);



