<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MarketingSpending;
use App\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MarketingSpendingController extends Controller
{
    public function index(Request $request)
    {
        try {
            // 1. Ensure the year from the request is treated as an integer.
            $year = (int)$request->query('year', date('Y')); 
            
            Log::info('Marketing Spending Index called', ['year' => $year]);

            // Fetch spendings for the requested year
            $spendings = MarketingSpending::with('leadSource')
                ->where('year', $year)
                ->get();

            Log::info('Spendings fetched', ['count' => $spendings->count()]);

            // 2. Add an explicit order for lead sources.
            $leadSources = LeadSource::orderBy('name')->get(); 

            Log::info('Lead sources fetched', ['count' => $leadSources->count()]);

            // Fetch all distinct years present in the marketing_spendings table
            $years = MarketingSpending::select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            Log::info('Years fetched', ['years' => $years]);

            // 3. Ensure the current/requested year is available
            if (!$years->contains($year)) {
                $years = $years->push($year)->sort()->reverse()->values();
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Marketing spendings fetched successfully',
                'spendings' => $spendings,
                'leadSources' => $leadSources,
                'years' => $years->values()->all(), // Ensure clean array keys
            ]);
            
        } catch (\Exception $e) {
            Log::error('Marketing Spending Index Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching marketing spendings',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function index_View(Request $request)
    {
        try {
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
                'years' => $years->all(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Marketing Spending Index View Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
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
            
        } catch (\Exception $e) {
            Log::error('Marketing Spending Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while saving',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}