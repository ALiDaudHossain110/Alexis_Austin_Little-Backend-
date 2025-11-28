<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MarketingSpending;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarketingSpendingController extends Controller
{



public function index(Request $request)
{
    // 1. Ensure the year from the request is treated as an integer.
    $year = (int)$request->query('year', date('Y')); 

    // Fetch spendings for the requested year
    $spendings = MarketingSpending::with('leadSource')
        ->where('year', $year)
        ->get();

    // 2. Add an explicit order for lead sources.
    $leadSources = LeadSource::orderBy('name')->get(); 

    // Fetch all distinct years present in the marketing_spendings table
    $years = MarketingSpending::select('year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

    // 3. Ensure the current/requested year is available in the dropdown even if no data exists yet.
    if (!$years->contains($year)) {
        $years->prepend($year);
    }
    
    // ... rest of the code is fine
    return response()->json([
        'status' => true,
        'message' => 'Marketing spendings fetched successfully',
        'spendings' => $spendings,
        'leadSources' => $leadSources,
        'years' => $years->values(), // Use values() to ensure clean array keys
    ]);
}
        public function index_View(Request $request)
    {

        // Fetch spendings for the requested year
        $spendings = MarketingSpending::with('leadSource')->get();

        // Fetch all lead sources
        $leadSources = LeadSource::all();

        // Fetch all distinct years present in the marketing_spendings table
        $years = MarketingSpending::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json([
            'status' => true,
            'message' => 'Marketing spendings fetched successfully',
            'spendings' => $spendings,
            'leadSources' => $leadSources,
            'years' => $years, // add years for the frontend dropdown
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_source_id' => 'required|exists:lead_sources,id',
            'year' => 'required|integer',
            'january' => 'nullable|numeric',
            'february' => 'nullable|numeric',
            'march' => 'nullable|numeric',
            'april' => 'nullable|numeric',
            'may' => 'nullable|numeric',
            'june' => 'nullable|numeric',
            'july' => 'nullable|numeric',
            'august' => 'nullable|numeric',
            'september' => 'nullable|numeric',
            'october' => 'nullable|numeric',
            'november' => 'nullable|numeric',
            'december' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $months = [
            'january','february','march','april','may','june',
            'july','august','september','october','november','december'
        ];

        $monthData = [];
        foreach ($months as $month) {
            $monthData[$month] = $request->filled($month) ? $request->input($month) : null;
        }

        $spending = MarketingSpending::updateOrCreate(
            ['lead_source_id' => $request->lead_source_id, 'year' => $request->year],
            $monthData
        );

        return response()->json([
            'status' => true,
            'message' => 'Marketing spending saved successfully',
            'data' => $spending,
        ]);
    }
}
