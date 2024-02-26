<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function getAllCategories(Request $request) {
        //retrieve yung query params
        $name = $request->input('name');
        $description = $request->input('description');

        //make queries optional
        $query = Category::query();
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($description !== null) {
            $query->where('description', 'like', '%' . $description . '%');
        }

        // Execute the query and retrieve categories
        $categories = $query->get();

        // Return the filtered categories
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function getCategory($id) {
        // find category by its id
        $category = Category::find($id);

        // check if category exist, if not return 404
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // send category as json
        return response()->json(['category' => $category]);
    }

    public function createCategory(Request $request) {
        //validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            //create new category
            $category = new Category();
            $category->name = $request->input('name');
            $category->description = $request->input('description');

            //save to DB
            $category->save();

            //return success message
            return response()->json(['message' => 'Category created successfully'], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to create category. Database error.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create category. Unexpected error.'], 500);
        }
    }

    public function updateCategory(Request $request, $id) {
        //find the category by its id
        $category = Category::find($id);

        //check if it was found
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        //check kung may valid name
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            //update the fields
            $category->name = $request->input('name');
            $category->description = $request->input('description');

            //save sa DB
            $category->save();

            // Return success message
            return response()->json(['message' => 'Category updated successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to update category. Database error.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category. Unexpected error.'], 500);
        }
    }

    public function deleteCategory($id) {
        //find the category by its id
        $category = Category::find($id);

        //check if it was found
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        try {
            //delete in DB
            $category->delete();

            // Return success message
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete category. Unexpected error.'], 500);
        }
    }
}
