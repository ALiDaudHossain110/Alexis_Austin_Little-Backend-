<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MarketingSpending;
use App\Models\LeadSource; // ✅ FIXED IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MarketingSpendingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $year = (int) $request->query('year', date('Y'));

            $spendings = MarketingSpending::with('leadSource')
                ->where('year', $year)
                ->get();

            $leadSources = LeadSource::orderBy('name')->get();

            $years = MarketingSpending::select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();

            if (!in_array($year, $years)) {
                $years[] = $year;
                rsort($years);
            }

            return response()->json([
                'status' => true,
                'message' => 'Marketing spendings fetched successfully',
                'spendings' => $spendings,
                'leadSources' => $leadSources,
                'years' => array_values($years),
            ]);

        } catch (\Exception $e) {
            Log::error('Marketing Spending Index Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching marketing spendings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index_View(Request $request)
    {
        try {
            $spendings = MarketingSpending::with('leadSource')->get();
            $leadSources = LeadSource::all();

            $years = MarketingSpending::select('year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Marketing spendings fetched successfully',
                'spendings' => $spendings,
                'leadSources' => $leadSources,
                'years' => $years,
            ]);

        } catch (\Exception $e) {
            Log::error('Marketing Spending Index View Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
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
                [
                    'lead_source_id' => $request->lead_source_id,
                    'year' => $request->year
                ],
                $monthData
            );

            return response()->json([
                'status' => true,
                'message' => 'Marketing spending saved successfully',
                'data' => $spending,
            ]);

        } catch (\Exception $e) {
            Log::error('Marketing Spending Store Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while saving',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
  