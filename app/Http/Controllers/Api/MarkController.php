<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function newMark(Request $request)
    {
        try {
            $validateData = Validator::make($request->all(),[
                'user_id' => 'required|integer|exists:users,id',
                'name'    => 'required|string',
                'supermarket' => 'required|string',
                'category' => 'required|string'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            $newMark = new Mark();
            foreach (array_keys($request->all()) as $key => $value) {
                $newMark->$value = $request[$value];
            }
            $newMark->save();
            return response()->json(
                ['message' => 'Success operation', 'data' => $newMark], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function getListMark(int $userId)
    {
        try {
            $validateData = Validator::make(['user_id' => $userId], [
                'user_id' => 'required|integer|exists:users,id'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            $list = Mark::where('user_id', '=', $userId)->get();
            return response()->json(['message' => 'Success operation', 'data' => $list], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function getMark(int $id)
    {
        try {
            $validateData = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:marks,id'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            $mark = Mark::find($id)->first();
            return response()->json(['message' => 'Success operation', 'data' => $mark], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function editMark(Request $request, $id)
    {
        try {
            $validateData = Validator::make($request->all(), [
                'name'    => 'required|string',
                'supermarket' => 'required|string',
                'category' => 'required|string'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            Mark::where('id', '=', $id)->update($request->all());
            $mark = Mark::findOrFail($id);
            return response()->json(['message' => 'Success operation', 'data' => $mark], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteMark(int $id)
    {
        try {
            $validateData = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:marks,id'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            Mark::where('id', '=', $id)->delete();
            return response()->json(['message' => 'Success operation', 'data' => 'OK'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
