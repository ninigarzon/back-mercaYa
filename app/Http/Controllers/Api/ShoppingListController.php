<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShoppingList;

class ShoppingListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        try{
            $validateData = Validator::make($request->all(), [
                '*.user_id' => 'required|integer|exists:users,id',
                '*.mark_id' => 'required|integer|exists:marks,id',
                '*.product_id' => 'required|integer|exists:marks,id',
                '*.list_name' => 'required|string',
                '*.mark_name' => 'required|string',
                '*.product_name' => 'required|string',
                '*.supermarket' => 'required|string',
                '*.amount' => 'required|integer',
            ]);
            if($validateData->fails()){
                return response()->json($validateData->errors(), 403);
            }
            $newShoppingList = new ShoppingList();
            foreach (array_keys($request->all()) as $key => $value) {
                $newProduct->$value =  $request[$value];
            }
            $newProduct->save();
            return response()->json(['message' => 'Success operation', 'data' => $newShoppingList], 201);
        }
        catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function index(int $id)
    {
        try{
            $validateData = Validator::make(['id' => $id],[
                'id' => 'required|integer|exists:shopping_list,id'
            ]);
            if($validateData->fails()){
                return response()->json($validateData->errors(), 403);
            }
            $shoppingList = ShoppingList::find($id)->first();
            return response()->json(['message' => 'Success operation', 'data' => $shoppingList], 201);
        }catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function getList(int $userId)
    {
        try {
            $validateData = Validator::make(['user_id' => $userId], [
                'user_id' => 'required|integer|exists:users,id'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            $list = ShoppingList::where('user_id', '=', $userId)->get();
            return response()->json(['message' => 'Success operation', 'data' => $list], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $validateData = Validator::make($request->all(), [
                '*.list_name' => 'required|string',
                '*.mark_name' => 'required|string',
                '*.product_name' => 'required|string',
                '*.supermarket' => 'required|string',
                '*.amount' => 'required|integer',
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            ShoppingList::where('id', '=', $id)->update($request->all());
            $shoppingListUpdate = ShoppingList::findOrFail($id);
            return response()->json(['message' => 'Success operation', 'data' => $shoppingListUpdate], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function delete(int $id)
    {
        try {
            $validateData = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:shopping_list,id'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            ShoppingList::where('id', '=', $id)->delete();
            return response()->json(['message' => 'Success operation', 'data' => 'OK'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
