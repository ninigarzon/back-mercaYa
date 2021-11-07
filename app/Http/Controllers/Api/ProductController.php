<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->middleware('auth:api');
        $this->product = $product;
    }

    public function store(Request $request)
    {
        try{
            $validateData = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'name => ' => 'required|string',
            ]);
            if($validateData->fails()){
                return response()->json($validateData->errors(), 403);
            }
            foreach (array_keys($request->all()) as $key => $value) {
                $this->product->$value =  $request[$value];
            }
            $this->product->save();
            return response()->json(['message' => 'create value'], 201);
        }
        catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function index(int $id)
    {
        try{
            $validateData = Validator::make(['id' => $id],[
                'id' => 'required|integer|exists:marks,id'
            ]);
            if($validateData->fails()){
                return response()->json($validateData->errors(), 403);
            }
            $products = $this->product::find($id)->first();
            return response()->json(['data' => $products], 201);
        }catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function getList(int $userId)
    {
        try {
            $validateData = Validator::make([])
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function update(Request $request, int $id)
    {
        # code...
    }

    public function delete(int $id)
    {
        # code...
    }
}
