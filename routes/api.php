<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', [CategoryController::class, 'getAllCategories']);
Route::get('/categories/{id}', [CategoryController::class, 'getCategory']);
Route::post('/categories', [CategoryController::class, 'createCategory']);
Route::put('/categories/{id}', [CategoryController::class, 'updateCategory']);
Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);

Route::get('/items', [ItemController::class, 'getAllItems']);
Route::get('/items/{id}', [ItemController::class, 'getItem']);
Route::get('/itemsWithCategory', [ItemController::class, 'getItemsWithCategoryDetails']);
Route::post('/items', [ItemController::class, 'createItem']);
Route::put('/items/{id}', [ItemController::class, 'updateItem']);
Route::delete('/items/{id}', [ItemController::class, 'deleteItem']);

