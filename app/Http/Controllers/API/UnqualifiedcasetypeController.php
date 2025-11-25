<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Unqualifiedcasetype;

class UnqualifiedcasetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Unqualifiedcasetype::all();

        return response()->json([
            'status'  => true,
            'message' => 'All Unqualified Case Types',
            'data'    => $items,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:unqualifiedcasetypes,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item = Unqualifiedcasetype::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Unqualified Case Type created successfully',
            'data'    => $item,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Unqualifiedcasetype::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Unqualified Case Type not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $item,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Unqualifiedcasetype::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Unqualified Case Type not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:unqualifiedcasetypes,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item->name = $request->name;
        $item->save();

        return response()->json([
            'status'  => true,
            'message' => 'Unqualified Case Type updated successfully',
            'data'    => $item,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Unqualifiedcasetype::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Unqualified Case Type not found',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Unqualified Case Type deleted successfully',
        ], 200);
    }
}
