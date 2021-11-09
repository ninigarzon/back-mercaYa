<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        try{
            $validateData = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'mark_id' => 'required|integer|exists:marks,id',
                'name' => 'required|string',
                'current_amount' => 'required|integer',
                'ideal_amount' => 'required|integer',
            ]);
            if($validateData->fails()){
                return response()->json($validateData->errors(), 403);
            }
            $newProduct = new Product();
            foreach (array_keys($request->all()) as $key => $value) {
                $newProduct->$value =  $request[$value];
            }
            $newProduct->save();
            return response()->json(['message' => 'Success operation', 'data' => $newProduct], 201);
        }
        catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function index(int $id)
    {
        try{
            $validateData = Validator::make(['id' => $id],[
                'id' => 'required|integer|exists:product,id'
            ]);
            if($validateData->fails()){
                return response()->json($validateData->errors(), 403);
            }
            $products = Product::find($id)->first();
            return response()->json(['message' => 'Success operation', 'data' => $products], 201);
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
            $list = Product::where('user_id', '=', $userId)->get();
            return response()->json(['message' => 'Success operation', 'data' => $list], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $validateData = Validator::make($request->all(), [
                'name' => 'required|string',
                'current_amount' => 'required|integer',
                'ideal_amount' => 'required|integer',
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            Product::where('id', '=', $id)->update($request->all());
            $productUpdate = Product::findOrFail($id);
            return response()->json(['message' => 'Success operation', 'data' => $productUpdate], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function delete(int $id)
    {
        try {
            $validateData = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:product,id'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            Product::where('id', '=', $id)->delete();
            return response()->json(['message' => 'Success operation', 'data' => 'OK'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
