<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


Route::post('login',[LoginController::class,'login']);



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-categories', [CategoryController::class, 'getCategories']);
//Route::post('get-categories', [CategoryController::class, 'productsWithFilters']);

Route::post('get-categories-products', [CategoryController::class, 'productsWithFilters']);

Route::get('get-product/{name}', [ProductController::class, 'getProductBySlug']);
// Route::get('category/{category-name}', [CategoryController::class, 'getCategories']);
Route::prefix("categories")->group(function(){
    Route::post('add', [CategoryController::class, 'storeCategory']);
});


Route::prefix('admin')->middleware([])->group(function (){
    Route::post('/products/store', [ProductController::class, 'store'])->name('admin.products.create');
    //Route::post('/products/store', [ProductController::class, 'store'])->name('admin.products.store');
});
