<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
// use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function getAllItems(Request $request) {
        //retrieve yung query params
        $name = $request->input('name');
        $description = $request->input('description');

        //make queries optional
        $query = Item::query();
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($description !== null) {
            $query->where('description', 'like', '%' . $description . '%');
        }

        // Execute the query and retrieve items
        $items = $query->get();

        // Return the filtered items
        return response()->json([
            'items' => $items
        ]);
    }

    public function getItem($id) {
        // find item by its id
        $item = Item::find($id);

        // check if item exist, if not return 404
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // send item as json
        return response()->json(['item' => $item]);
    }

    // public function getItemsWithCategoryDetails() {
    //     // Perform $lookup aggregation
    //     $itemsWithCategoryDetails = Item::raw(function ($collection) {
    //         return $collection->aggregate([
    //             [
    //                 '$lookup' => [
    //                     'from' => 'categories',
    //                     'localField' => 'category_id',
    //                     'foreignField' => '_id',
    //                     'as' => 'category',
    //                 ],
    //             ],
    //             [
    //                 '$unwind' => '$category',
    //             ],
    //             [
    //                 '$project' => [
    //                     'name' => 1,
    //                     'description' => 1,
    //                     'price' => 1,
    //                     'quantity' => 1,
    //                     'category_id' => 1,
    //                     'category' => '$category',
    //                 ],
    //             ],
    //         ]);
    //     });

    //     //return items with category details
    //     return response()->json(['items' => $itemsWithCategoryDetails]);
    // }

    public function createItem(Request $request) {
        //validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            //create new item
            $item = new Item();
            $item->name = $request->input('name');
            $item->description = $request->input('description');
            $item->price = $request->input('price');
            $item->quantity = $request->input('quantity');

            $category_id = $request->input('category_id');
            // dd($category_id);
            $item->category_id = new ObjectId($category_id);

            //save to DB
            $item->save();

            //return success message
            return response()->json(['message' => 'Item created successfully'], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to create item. Database error.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create item. Unexpected error.'], 500);
        }
    }

    public function updateItem(Request $request, $id) {
        //find the item by its id
        $item = Item::find($id);

        //check if it was found
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        //check kung may valid name
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string|max:255',
            'price' => 'numeric',
            'quantity' => 'integer',
            'category_id' => 'string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            //update the fields
            $item->name = $request->input('name');
            $item->description = $request->input('description');
            $item->price = $request->input('price');
            $item->quantity = $request->input('quantity');
            $item->category_id = new ObjectId($request->input('category_id'));

            //save sa DB
            $item->save();

            // Return success message
            return response()->json(['message' => 'Item updated successfully'], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to update item. Database error.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update item. Unexpected error.'], 500);
        }
    }

    public function deleteItem($id) {
        //find the item by its id
        $item = Item::find($id);

        //check if it was found
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        try {
            //delete in DB
            $item->delete();

            // Return success message
            return response()->json(['message' => 'Item deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete item. Unexpected error.'], 500);
        }
    }

    public function getItemsWithCategoryDetails() {
        // Perform $lookup aggregation
        $itemsWithCategoryDetails = Item::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'category',
                        'localField' => 'category_id',
                        'foreignField' => '_id',
                        'as' => 'category',
                    ],
                ],
                [
                    '$unwind' => '$category',
                ],
                [
                    '$project' => [
                        'name' => 1,
                        'description' => 1,
                        'price' => 1,
                        'quantity' => 1,
                        'category_id' => 1,
                        'category' => '$category',
                    ],
                ],
            ]);
        });
    
        // Log the result for debugging
        Log::info($itemsWithCategoryDetails);
    
        //return items with category details
        return response()->json(['items' => $itemsWithCategoryDetails]);
    }
}
