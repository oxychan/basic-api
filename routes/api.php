<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;


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

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Routes for products 
Route::get('/products/search/{name}', [ProductController::class, 'search'])->name('products.search');
Route::resource('/products', ProductController::class)->only(['index', 'show']);

// Routes for register and login


// Protected Routes
// Group of routes that guarded by auth sanctum
Route::group(['middleware' => ['auth:sanctum']], function() {
    // Routes for products
    Route::resource('/products', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

// Will be execute when no routes matches
Route::fallback(function() {
    abort(404);
});
