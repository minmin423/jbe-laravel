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

//CATEGORIES

// Get all categories
Route::get('/categories', [CategoryController::class, 'getAllCategories']);
// Get a specific category by its ID
Route::get('/categories/{id}', [CategoryController::class, 'getCategory']);
// Create a new category
Route::post('/categories', [CategoryController::class, 'createCategory']);
// Update an existing category by its ID
Route::put('/categories/{id}', [CategoryController::class, 'updateCategory']);
// Delete a category by its ID
Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);

//ITEMS

// Get all items
Route::get('/items', [ItemController::class, 'getAllItems']);
// Get a specific item by its ID
Route::get('/items/{id}', [ItemController::class, 'getItem']);
// Get items with associated category details using $lookup aggregation
Route::get('/itemsWithCategory', [ItemController::class, 'getItemsWithCategoryDetails']);
// Create a new item
Route::post('/items', [ItemController::class, 'createItem']);
// Update an existing item by its ID
Route::put('/items/{id}', [ItemController::class, 'updateItem']);
// Delete an item by its ID
Route::delete('/items/{id}', [ItemController::class, 'deleteItem']);


