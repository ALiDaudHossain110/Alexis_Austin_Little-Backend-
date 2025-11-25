<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LawFirm;
use Illuminate\Http\Request;

class LawFirmController extends Controller
{
    // ✅ Fetch all law firms
    public function index()
    {
        $lawFirms = LawFirm::all();
        return response()->json([
            'status' => true,
            'message' => 'Law firms fetched successfully',
            'data' => $lawFirms,
        ]);
    }

    // ✅ Create a new law firm
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:law_firm,email',
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|string|max:255',
        ]);

        $lawFirm = LawFirm::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Law firm created successfully',
            'data' => $lawFirm,
        ], 201);
    }

    // ✅ Fetch single law firm
    public function show($id)
    {
        $lawFirm = LawFirm::find($id);

        if (!$lawFirm) {
            return response()->json([
                'status' => false,
                'message' => 'Law firm not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $lawFirm,
        ]);
    }

    // ✅ Update law firm
    public function update(Request $request, $id)
    {
        $lawFirm = LawFirm::find($id);

        if (!$lawFirm) {
            return response()->json([
                'status' => false,
                'message' => 'Law firm not found',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:law_firm,email,' . $lawFirm->id,
            'phone' => 'nullable|string|max:30',
            'website' => 'nullable|string|max:255',
        ]);

        $lawFirm->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Law firm updated successfully',
            'data' => $lawFirm,
        ]);
    }

    // ✅ Delete law firm
    public function destroy($id)
    {
        $lawFirm = LawFirm::find($id);

        if (!$lawFirm) {
            return response()->json([
                'status' => false,
                'message' => 'Law firm not found',
            ], 404);
        }

        $lawFirm->delete();

        return response()->json([
            'status' => true,
            'message' => 'Law firm deleted successfully',
        ]);
    }
}
