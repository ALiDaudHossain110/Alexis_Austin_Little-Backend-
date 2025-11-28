<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\API\AuthController;
use  App\Http\Controllers\API\LeadsourceController;
use  App\Http\Controllers\API\UnqualifiedcasetypeController;
use  App\Http\Controllers\API\LocationservingController;
use  App\Http\Controllers\API\CasetypeController;
use App\Http\Controllers\API\LeadController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MarketingSpendingController;
use App\Http\Controllers\API\LawFirmController;
use App\Http\Controllers\API\AIInsightController;


Route::post('signup',[AuthController::class,'signup']);
Route::post('login',[AuthController::class,'login']);
Route::post('/law-firms', [LawFirmController::class, 'store']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/marketing-spendings', [MarketingSpendingController::class, 'index']);
    Route::get('/marketing-spendings_view', [MarketingSpendingController::class, 'index_View']);
    Route::post('/marketing-spendings', [MarketingSpendingController::class, 'store']);
    Route::get('/law-firms', [LawFirmController::class, 'index']);
    Route::get('/law-firms/{id}', [LawFirmController::class, 'show']);
    Route::put('/law-firms/{id}', [LawFirmController::class, 'update']);
    Route::delete('/law-firms/{id}', [LawFirmController::class, 'destroy']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/employees', [AuthController::class, 'createEmployee']);
    Route::get('/employees', [AuthController::class, 'listEmployees']);
    Route::put('/employees/{id}', [AuthController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('me', [AuthController::class, 'me']);
    Route::get('getUser', [UserController::class, 'getUser']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::apiResource('leadsourcedatas',LeadsourceController::class);
    Route::apiResource('unqualifiedcasetypes',UnqualifiedcasetypeController::class);
    Route::apiResource('Locationservings',LocationservingController::class);
    Route::apiResource('Casetypes',CasetypeController::class);
    Route::apiResource('leads', LeadController::class);


    Route::post('/ai/lead-insights', [AIInsightController::class, 'generateLeadInsights']);
    Route::post('/ai/RevenuebyCase-lead-insights', [AIInsightController::class, 'generateRevenuebyCaseInsights']);
    Route::post('/ai/MarketingSourcePerformance-lead-insights', [AIInsightController::class, 'generateMarketingSourcePerformanceInsights']);
    Route::post('/ai/StaffPerformance-lead-insights', [AIInsightController::class, 'StaffPerformanceInsights']);
    Route::post('/ai/CaseTypeAnalytics-lead-insights', [AIInsightController::class, 'CaseTypeAnalyticsInsights']);
    Route::post('/ai/LocationAnalytics-lead-insights', [AIInsightController::class, 'LocationAnalyticsInsights']);
    Route::post('/ai/FinancialPerformanceROI-lead-insights', [AIInsightController::class, 'FinancialPerformanceROIInsights']);
    Route::post('/ai/clientConversionRate-lead-insights', [AIInsightController::class, 'ClientConversionRateInsights']);
    Route::post('/ai/FollowUpEffectiveness-lead-insights', [AIInsightController::class, 'FollowUpEffectivenessInsights']);


});




