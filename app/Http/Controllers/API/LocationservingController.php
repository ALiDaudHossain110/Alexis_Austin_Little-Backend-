<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Locationserving;

class LocationservingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Locationserving::all();

        return response()->json([
            'status'  => true,
            'message' => 'All Location Servings',
            'data'    => $items,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_name' => 'required|string|max:255',
            'city_name' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item = Locationserving::create([
            'state_name' => $request->state_name,
            'city_name' => $request->city_name,
            'zip_code' => $request->zip_code,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Location Serving created successfully',
            'data'    => $item,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Locationserving::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Location Serving not found',
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
        $item = Locationserving::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Location Serving not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'state_name' => 'required|string|max:255' . $id,
            'city_name' => 'required|string|max:255' . $id,
            'zip_code' => 'required|string|max:255' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $item->state_name = $request->state_name;
        $item->city_name = $request->city_name;
        $item->zip_code = $request->zip_code;
        $item->save();

        return response()->json([
            'status'  => true,
            'message' => 'Location Serving updated successfully',
            'data'    => $item,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Locationserving::find($id);

        if (!$item) {
            return response()->json([
                'status'  => false,
                'message' => 'Location Serving not found',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Location Serving deleted successfully',
        ], 200);
    }
}
