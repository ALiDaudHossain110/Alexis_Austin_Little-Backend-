<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIInsightController extends Controller
{
    public function generateLeadInsights(Request $request)
    {
        $validated = $request->validate([
            'totalInfo' => 'required|array',
            'leadConversionRevenue' => 'required|array',
            'leadConversionSummary' => 'required|array'
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
        You are a Senior Law Firm Growth Strategist and Data Analyst.

        Analyze the following data:

        TOTAL INFO:
        " . json_encode($validated['totalInfo']) . "

        CASE TYPE BREAKDOWN (leadConversionRevenue):
        " . json_encode($validated['leadConversionRevenue']) . "

        OVERALL FUNNEL SUMMARY (leadConversionSummary):
        " . json_encode($validated['leadConversionSummary']) . "

        --- OUTPUT RULES ---
        You MUST return a valid JSON ONLY in this exact structure:

        {
          \"insight\": [
            \"insight 1 short and data-based\",
            \"insight 2 short and data-based\",
            \"insight 3 short and data-based\"
            \"insight 4 short and data-based\"
            \"insight 5 short and data-based\"
          ],
          \"recommendation\": [
            \"recommendation 1 based on insights\",
            \"recommendation 2 based on insights\",
            \"recommendation 3 based on insights\"
            \"recommendation 4 based on insights\"
            \"recommendation 5 based on insights\"
          ],
          \"status\": \"excellent | good | warning | critical\"
        }

        --- STATUS LOGIC ---
        - excellent → high revenue, high conversion, strong ROI
        - good → average performance with small gaps
        - warning → low leads, revenue concentrated in 1 category
        - critical → extremely low leads, low revenue, poor conversion

        STRICT RULES:
        - No markdown
        - No extra text outside JSON
        - No explanation
        - Insights and recommendations MUST use only the provided data
        ";

        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateRevenueByCaseInsights(Request $request)
    {
        $validated = $request->validate([
            'leadsRevenue' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
            You are a Senior Law Firm Growth Strategist and Data Analyst.

            Analyze the following revenue data by case type:

            " . json_encode($validated['leadsRevenue']) . "

            --- OUTPUT RULES ---
            You MUST return a valid JSON ONLY in this exact structure:

            {
            \"insight\": [
                \"insight 1 short and data-based\",
                \"insight 2 short and data-based\",
                \"insight 3 short and data-based\"
                \"insight 4 short and data-based\"
                \"insight 5 short and data-based\"
            ],
            \"recommendation\": [
                \"recommendation 1 based on insights\",
                \"recommendation 2 based on insights\",
                \"recommendation 3 based on insights\"
                \"recommendation 4 based on insights\"
                \"recommendation 5 based on insights\"
            ],
            \"status\": \"excellent | good | warning | critical\"
            }

            --- STATUS LOGIC ---
            - excellent → revenue is well-distributed across multiple case types and total revenue is high
            - good → revenue is moderate with minor concentration in some case types
            - warning → revenue heavily concentrated in 1 or 2 case types
            - critical → extremely low revenue or almost all revenue from a single case type

            STRICT RULES:
            - No markdown
            - No extra text outside JSON
            - Insights and recommendations MUST use only the provided data
            ";

        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }



    public function generateMarketingSourcePerformanceInsights(Request $request)
    {
        $validated = $request->validate([
            'MarketingSourcePerformance' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
            You are a Senior Law Firm Growth Strategist and Data Analyst.

            Analyze the following Marketing Source Performance by Marketing Source Performance type:

            " . json_encode($validated['MarketingSourcePerformance']) . "

            --- OUTPUT RULES ---
            You MUST return a valid JSON ONLY in this exact structure:

            {
            \"insight\": [
                \"insight 1 short and data-based\",
                \"insight 2 short and data-based\",
                \"insight 3 short and data-based\"
                \"insight 4 short and data-based\"
                \"insight 5 short and data-based\"
            ],
            \"recommendation\": [
                \"recommendation 1 based on insights\",
                \"recommendation 2 based on insights\",
                \"recommendation 3 based on insights\"
                \"recommendation 4 based on insights\"
                \"recommendation 5 based on insights\"
            ],
            \"status\": \"excellent | good | warning | critical\"
            }

            --- STATUS LOGIC ---
            - excellent → revenue is well-distributed across multiple case types and total revenue is high
            - good → revenue is moderate with minor concentration in some case types
            - warning → revenue heavily concentrated in 1 or 2 case types
            - critical → extremely low revenue or almost all revenue from a single case type

            STRICT RULES:
            - No markdown
            - No extra text outside JSON
            - Insights and recommendations MUST use only the provided data
            ";

        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function StaffPerformanceInsights(Request $request)
    {
        $validated = $request->validate([
            'StaffPerformance' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
            You are a Senior Law Firm Growth Strategist and Data Analyst.

            Analyze the following Staff Performance by user names and the number of cases they have consulted and the number of cases they have converted type:

            " . json_encode($validated['StaffPerformance']) . "

            --- OUTPUT RULES ---
            You MUST return a valid JSON ONLY in this exact structure:

            {
            \"insight\": [
                \"insight 1 short and data-based\",
                \"insight 2 short and data-based\",
                \"insight 3 short and data-based\"
                \"insight 4 short and data-based\"
                \"insight 5 short and data-based\"
            ],
            \"recommendation\": [
                \"recommendation 1 based on insights\",
                \"recommendation 2 based on insights\",
                \"recommendation 3 based on insights\"
                \"recommendation 4 based on insights\"
                \"recommendation 5 based on insights\"
            ],
            \"status\": \"excellent | good | warning | critical\"
            }

            --- STATUS LOGIC ---
            - excellent → revenue is well-distributed across multiple case types and total revenue is high
            - good → revenue is moderate with minor concentration in some case types
            - warning → revenue heavily concentrated in 1 or 2 case types
            - critical → extremely low revenue or almost all revenue from a single case type

            STRICT RULES:
            - No markdown
            - No extra text outside JSON
            - Insights and recommendations MUST use only the provided data
            ";

        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }    
    
    public function CaseTypeAnalyticsInsights(Request $request)
    {
        $validated = $request->validate([
            'CaseTypeAnalytics' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
            You are a Senior Law Firm Growth Strategist and Data Analyst.

            Analyze the following Consultation, Av follow ups & Conversion by Case Type :

            " . json_encode($validated['CaseTypeAnalytics']) . "

            --- OUTPUT RULES ---
            You MUST return a valid JSON ONLY in this exact structure:

            {
            \"insight\": [
                \"insight 1 short and data-based\",
                \"insight 2 short and data-based\",
                \"insight 3 short and data-based\"
                \"insight 4 short and data-based\"
                \"insight 5 short and data-based\"
            ],
            \"recommendation\": [
                \"recommendation 1 based on insights\",
                \"recommendation 2 based on insights\",
                \"recommendation 3 based on insights\"
                \"recommendation 4 based on insights\"
                \"recommendation 5 based on insights\"
            ],
            \"status\": \"excellent | good | warning | critical\"
            }

            --- STATUS LOGIC ---
            - excellent → revenue is well-distributed across multiple case types and total revenue is high
            - good → revenue is moderate with minor concentration in some case types
            - warning → revenue heavily concentrated in 1 or 2 case types
            - critical → extremely low revenue or almost all revenue from a single case type

            STRICT RULES:
            - No markdown
            - No extra text outside JSON
            - Insights and recommendations MUST use only the provided data
            ";

        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }

    public function LocationAnalyticsInsights(Request $request)
    {
        $validated = $request->validate([
            'LocationAnalytics' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
            You are a Senior Law Firm Growth Strategist and Data Analyst.

            Analyze the following Consultation, consultation And Conversion by Location Type :

            " . json_encode($validated['LocationAnalytics']) . "

            --- OUTPUT RULES ---
            You MUST return a valid JSON ONLY in this exact structure:

            {
            \"insight\": [
                \"insight 1 short and data-based\",
                \"insight 2 short and data-based\",
                \"insight 3 short and data-based\"
                \"insight 4 short and data-based\"
                \"insight 5 short and data-based\"
            ],
            \"recommendation\": [
                \"recommendation 1 based on insights\",
                \"recommendation 2 based on insights\",
                \"recommendation 3 based on insights\"
                \"recommendation 4 based on insights\"
                \"recommendation 5 based on insights\"
            ],
            \"status\": \"excellent | good | warning | critical\"
            }

            --- STATUS LOGIC ---
            - excellent → revenue is well-distributed across multiple case types and total revenue is high
            - good → revenue is moderate with minor concentration in some case types
            - warning → revenue heavily concentrated in 1 or 2 case types
            - critical → extremely low revenue or almost all revenue from a single case type

            STRICT RULES:
            - No markdown
            - No extra text outside JSON
            - Insights and recommendations MUST use only the provided data
            ";

        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }


    public function FinancialPerformanceROIInsights(Request $request)
    {
        $validated = $request->validate([
            'FinancialPerformanceROI' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
        You are a Senior Law Firm Growth Strategist and Financial Data Analyst.

        Analyze the following financial performance dataset provided as JSON, which includes monthly revenues, marketing spendings, and ROI ((revenue - spend) / spend * 100) by year:

        " . json_encode($validated['FinancialPerformanceROI']) . "

        --- TASK ---
        Provide a deep, data-driven analysis with actionable insights. Focus on trends, growth patterns, ROI performance, revenue distribution across months and years, and any potential inefficiencies.

        --- OUTPUT RULES ---
        You MUST return valid JSON ONLY in the exact structure:

        {
        \"insight\": [
            \"insight 1: short, specific, and data-driven\",
            \"insight 2: short, specific, and data-driven\",
            \"insight 3: short, specific, and data-driven\",
            \"insight 4: short, specific, and data-driven\",
            \"insight 5: short, specific, and data-driven\"
        ],
        \"recommendation\": [
            \"recommendation 1: actionable and based on insights\",
            \"recommendation 2: actionable and based on insights\",
            \"recommendation 3: actionable and based on insights\",
            \"recommendation 4: actionable and based on insights\",
            \"recommendation 5: actionable and based on insights\"
        ],
        \"status\": \"excellent | good | warning | critical\"
        }

        --- STATUS LOGIC ---
        - excellent → consistent high revenue across multiple months and years with strong ROI
        - good → moderate revenue with some fluctuations or minor concentration in a few months
        - warning → uneven revenue with significant concentration in 1-2 months or low ROI periods
        - critical → very low total revenue, extremely poor ROI, or revenue almost entirely from a single month/year

        STRICT RULES:
        - No markdown or explanations outside JSON
        - Insights and recommendations MUST use only the provided data
        - Be concise, numeric, and specific where possible
        - Focus on actionable financial and marketing performance guidance
        ";


        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }



    public function ClientConversionRateInsights(Request $request)
    {
        $validated = $request->validate([
            'clientConversionRate' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
        You are a Senior Law Firm Growth Strategist and Financial Data Analyst.

        Analyze the following client Conversion Rate dataset provided as JSON, which includes total Consultations, total conversions, total average conversion rate:

        " . json_encode($validated['clientConversionRate']) . "

        --- TASK ---
        Provide a deep, data-driven analysis with actionable insights. Focus on trends, growth patterns, ROI performance, revenue distribution across months and years, and any potential inefficiencies.

        --- OUTPUT RULES ---
        You MUST return valid JSON ONLY in the exact structure:

        {
        \"insight\": [
            \"insight 1: short, specific, and data-driven\",
            \"insight 2: short, specific, and data-driven\",
            \"insight 3: short, specific, and data-driven\",
            \"insight 4: short, specific, and data-driven\",
            \"insight 5: short, specific, and data-driven\"
        ],
        \"recommendation\": [
            \"recommendation 1: actionable and based on insights\",
            \"recommendation 2: actionable and based on insights\",
            \"recommendation 3: actionable and based on insights\",
            \"recommendation 4: actionable and based on insights\",
            \"recommendation 5: actionable and based on insights\"
        ],
        \"status\": \"excellent | good | warning | critical\"
        }

        --- STATUS LOGIC ---
        - excellent → consistent high revenue across multiple months and years with strong ROI
        - good → moderate revenue with some fluctuations or minor concentration in a few months
        - warning → uneven revenue with significant concentration in 1-2 months or low ROI periods
        - critical → very low total revenue, extremely poor ROI, or revenue almost entirely from a single month/year

        STRICT RULES:
        - No markdown or explanations outside JSON
        - Insights and recommendations MUST use only the provided data
        - Be concise, numeric, and specific where possible
        - Focus on actionable financial and marketing performance guidance
        ";


        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }


    public function FollowUpEffectivenessInsights(Request $request)
    {
        $validated = $request->validate([
            'FollowUpEffectiveness' => 'required|array',
        ]);

        $apiKey = env('OPENAI_API_KEY');

        $prompt = "
        You are a Senior Law Firm Growth Strategist and Financial Data Analyst.

        Analyze the following FollowUp Effectiveness dataset provided as JSON, which includes total Consultations, total conversions, total average conversion rate of segments of 0, 1-2,3-5, 5++ followups:

        " . json_encode($validated['FollowUpEffectiveness']) . "

        --- TASK ---
        Provide a deep, data-driven analysis with actionable insights. Focus on trends, growth patterns, ROI performance, revenue distribution across months and years, and any potential inefficiencies.

        --- OUTPUT RULES ---
        You MUST return valid JSON ONLY in the exact structure:

        {
        \"insight\": [
            \"insight 1: short, specific, and data-driven\",
            \"insight 2: short, specific, and data-driven\",
            \"insight 3: short, specific, and data-driven\",
            \"insight 4: short, specific, and data-driven\",
            \"insight 5: short, specific, and data-driven\"
        ],
        \"recommendation\": [
            \"recommendation 1: actionable and based on insights\",
            \"recommendation 2: actionable and based on insights\",
            \"recommendation 3: actionable and based on insights\",
            \"recommendation 4: actionable and based on insights\",
            \"recommendation 5: actionable and based on insights\"
        ],
        \"status\": \"excellent | good | warning | critical\"
        }

        --- STATUS LOGIC ---
        - excellent → consistent high revenue across multiple months and years with strong ROI
        - good → moderate revenue with some fluctuations or minor concentration in a few months
        - warning → uneven revenue with significant concentration in 1-2 months or low ROI periods
        - critical → very low total revenue, extremely poor ROI, or revenue almost entirely from a single month/year

        STRICT RULES:
        - No markdown or explanations outside JSON
        - Insights and recommendations MUST use only the provided data
        - Be concise, numeric, and specific where possible
        - Focus on actionable financial and marketing performance guidance
        ";


        try {
            $response = Http::withToken($apiKey)
                ->post("https://api.openai.com/v1/responses", [
                    "model" => "gpt-4.1-mini",
                    "input" => [
                        [
                            "role" => "user",
                            "content" => [
                                ["type" => "input_text", "text" => $prompt]
                            ]
                        ]
                    ]
                ]);

            $json = $response->json();

            Log::info('AI Raw Response', ['response' => $json]);

            $aiText = $json['output'][0]['content'][0]['text'] ?? null;

            if (!$aiText) {
                return response()->json([
                    'error' => 'AI did not return a response',
                    'raw_response' => $json
                ], 500);
            }

            // Ensure response is valid JSON
            $structured = json_decode($aiText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'AI returned invalid JSON',
                    'raw_text' => $aiText
                ], 500);
            }

            return response()->json($structured);

        } catch (\Exception $e) {
            Log::error('AI Revenue Insight Error', ['message' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error calling AI: ' . $e->getMessage()
            ], 500);
        }
    }



}
