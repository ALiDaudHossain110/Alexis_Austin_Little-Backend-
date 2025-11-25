<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leadsource;
use Illuminate\Support\Facades\Storage;

class LeadsourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leadSources = Leadsource::all()->map(function ($leadsource) {
            return [
                'id' => $leadsource->id,
                'name' => $leadsource->name,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'All Lead Sources',
            'data' => $leadSources,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'  => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // $img = $request->file('image');
        // $imageName = time() . '.' . $img->getClientOriginalExtension();
        // $path = $img->storeAs('leadsource', $imageName, 'public'); // e.g. "leadsource/1695928373.jpg"

        // $leadsource = Leadsource::create([
        //     'name'  => $request->name,
        //     'image' => $path, // store full relative path
        // ]);

        $leadsource = Leadsource::create([
            'name'  => $request->name,
        ]);

        // return response()->json([
        //     'status'  => true,
        //     'message' => 'Leadsource has been successfully created',
        //     'data'    => [
        //         'id'        => $leadsource->id,
        //         'name'      => $leadsource->name,
        //         'image_url' => asset('storage/' . $leadsource->image),
        //     ],
        // ], 201);
        return response()->json([
            'status'  => true,
            'message' => 'Leadsource has been successfully created',
            'data'    => [
                'id'        => $leadsource->id,
                'name'      => $leadsource->name,
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leadsource = Leadsource::find($id);

        if (!$leadsource) {
            return response()->json([
                'status'  => false,
                'message' => 'Leadsource not found',
            ], 404);
        }

        // return response()->json([
        //     'status' => true,
        //     'data'   => [
        //         'id'        => $leadsource->id,
        //         'name'      => $leadsource->name,
        //         'image_url' => $leadsource->image ? asset('storage/' . $leadsource->image) : null,
        //     ],
        // ], 200);
        return response()->json([
            'status' => true,
            'data'   => [
                'id'        => $leadsource->id,
                'name'      => $leadsource->name,
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $leadsource = Leadsource::find($id);

        if (!$leadsource) {
            return response()->json([
                'status'  => false,
                'message' => 'Leadsource not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        if ($request->has('name')) {
            $leadsource->name = $request->name;
        }

        // if ($request->hasFile('image')) {
        //     $img = $request->file('image');
        //     $imageName = time() . '.' . $img->getClientOriginalExtension();
        //     $path = $img->storeAs('leadsource', $imageName, 'public');

        //     // Delete old image
        //     if ($leadsource->image && Storage::disk('public')->exists($leadsource->image)) {
        //         Storage::disk('public')->delete($leadsource->image);
        //     }

        //     $leadsource->image = $path;
        // }

        $leadsource->save();

        return response()->json([
            'status'  => true,
            'message' => 'Leadsource updated successfully',
            'data'    => [
                'id'        => $leadsource->id,
                'name'      => $leadsource->name,
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leadsource = Leadsource::find($id);

        if (!$leadsource) {
            return response()->json([
                'status'  => false,
                'message' => 'Leadsource not found',
            ], 404);
        }

        // if ($leadsource->image && Storage::disk('public')->exists($leadsource->image)) {
        //     Storage::disk('public')->delete($leadsource->image);
        // }

        $leadsource->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Leadsource deleted successfully',
        ], 200);
    }
}
