<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead;


class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = Lead::all();

        return response()->json([
            'status'  => true,
            'message' => 'All Leads',
            'data'    => $leads,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phonenumber' => 'required|string|max:20',
            'firstname'   => 'required|string|max:255',
            'lastname'    => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'casetype'    => 'nullable|string|max:255',
            'casevalue'   => 'nullable|numeric',
            'location'    => 'nullable|integer',
            'qualified'   => 'nullable|boolean',
            'unqualifiedcasetype' => 'nullable|string|max:255',
            'source'      => 'nullable|string|max:255',
            'consultbooked' => 'nullable|boolean',
            'converted'   => 'nullable|boolean',
            'converted_date' => 'nullable|date',
            'consultation_book_date' => 'nullable|date',
            'consultdone' => 'nullable|boolean',
            'user_id_consultantdoneby' => 'nullable|exists:users,id',
            'leadstatus'  => 'nullable|string|max:255',
            'number_of_follow_up_attempts'  => 'nullable|integer',
            'last_date_of_contact' => 'nullable|date',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $lead = Lead::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Lead created successfully',
            'data'    => $lead,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'status'  => false,
                'message' => 'Lead not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $lead,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'status'  => false,
                'message' => 'Lead not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'phonenumber' => 'sometimes|string|max:20',
            'firstname'   => 'sometimes|string|max:255',
            'lastname'    => 'sometimes|string|max:255',
            'email'       => 'nullable|email|max:255',
            'casetype'    => 'nullable|string|max:255',
            'casevalue'   => 'nullable|integer',
            'location'    => 'nullable|string|max:255',
            'qualified'   => 'boolean',
            'unqualifiedcasetype' => 'nullable|string|max:255',
            'source'      => 'nullable|string|max:255',
            'consultbooked' => 'boolean',
            'converted'   => 'boolean',
            'converted_date' => 'nullable|date',
            'consultation_book_date' => 'nullable|date',
            'consultdone' => 'boolean',
            'user_id_consultantdoneby' => 'nullable|exists:users,id',
            'leadsource'  => 'nullable|string|max:255',
            'leadstatus'  => 'nullable|string|max:255',
            'number_of_follow_up_attempts'  => 'nullable|integer|max:255',
            'last_date_of_contact' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $lead->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Lead updated successfully',
            'data'    => $lead,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'status'  => false,
                'message' => 'Lead not found',
            ], 404);
        }

        $lead->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Lead deleted successfully',
        ], 200);
    }
}
