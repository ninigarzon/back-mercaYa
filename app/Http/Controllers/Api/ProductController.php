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
        // $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        try{
            $validateData = Validator::make($request->all(), [
                'name_mark' => 'required|string',
                'price' => 'required',
                'quantity' => 'required|integer',
                'description' => 'nullable'
            ]);
            if($validateData->fails()){
                return response()->json($validateData->errors(), 403);
            }else {
                $newProduct = new Product($request->all());
                $newProduct->save();
                return response()->json(['message' => 'Success operation', 'data' => $newProduct], 201);
            }
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
            } else {
                $products = Product::findOrFail($id);
                return response()->json(['message' => 'Success operation', 'data' => $products], 201);
            }
        }catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function getList()
    {
        try {
            $list = Product::all();
            return response()->json(['message' => 'Success operation', 'data' => $list], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $validateData = Validator::make($request->all(), [
                'name_mark' => 'required|string',
                'price' => 'required',
                'quantity' => 'required|integer',
                'description' => 'nullable'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            Product::where('id', $id)->update($request->all());
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
            Product::findOrFail($id)->delete();
            return response()->json(['message' => 'Success operation', 'data' => 'OK'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
