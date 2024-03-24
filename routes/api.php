<?php

use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\UsersContoller;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Front\CartContoller;
use App\Http\Controllers\Api\AuthController;

Route::post('login',[LoginController::class,'login']);

Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::middleware(['auth:api','admin'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-categories', [CategoryController::class, 'getCategories']);
Route::post('products', [CategoryController::class, 'getProducts']);
//Route::post('get-categories', [CategoryController::class, 'productsWithFilters']);

Route::post('get-categories-products', [CategoryController::class, 'productsWithFilters']);

Route::get('get-product/{name}', [ProductController::class, 'getProductBySlug']);
// Route::get('category/{category-name}', [CategoryController::class, 'getCategories']);


Route::prefix('admin')->middleware(['auth:api','admin'])->group(function (){
    Route::prefix("categories")->group(function(){
        Route::post('add', [CategoryController::class, 'storeCategory']);
        Route::post('update', [CategoryController::class, 'update']);
        Route::post('update-status', [CategoryController::class, 'updateStatus']);
        Route::get('edit/{id}', [CategoryController::class, 'edit']);
    });
    Route::prefix("users")->group(function(){
        Route::post('/', [UsersContoller::class, 'index']);
        Route::get('edit/{id}', [UsersContoller::class, 'show']);
    });

    Route::post('dashboard', [AdminDashboardController::class, 'index']);
    Route::post('/products/store', [ProductController::class, 'store'])->name('admin.products.create');
    //Route::post('/products/store', [ProductController::class, 'store'])->name('admin.products.store');
});

//global routes

// Add to Cart
Route::post('/add-to-cart/{product}', [CartContoller::class, 'addToCart'])->name('addToCart');
Route::get('/get-cart', [CartContoller::class, 'getCart'])->name('getCart');

// Clear Cart
Route::post('/clear-cart', [CartContoller::class, 'clearCart'])->name('clearCart');
// Add to Favorites
Route::post('/add-to-favorites/{product}', [ProductController::class, 'addToFavorites'])->name('addToFavorites');



// Clear Favorites
Route::post('/clear-favorites', [ProductController::class, 'clearFavorites'])->name('clearFavorites');


Route::post('/remove-from-favorites/{product}', [ProductController::class, 'removeFromFavorites'])->name('removeFromFavorites');

