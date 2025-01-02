<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Api\v1\AnalysisController;
use Illuminate\Support\Facades\Artisan;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



/**
 * Accessible to non-authenticated users.
 */
Route::group([
    'prefix' => 'v1',
], function () {
    Route::post('/analysis', [App\Http\Api\v1\AnalysisController::class, 'create']);
    Route::post('/newAnalysis', [App\Http\Api\v1\AnalysisController::class, 'newAnalysis']);
    Route::get('/getAnalysis/{id}', [App\Http\Api\v1\AnalysisController::class, 'getAnalysis']);
    Route::delete('/deleteFile/{analysis_id}/{file_count}', [App\Http\Api\v1\AnalysisController::class, 'deleteFile']);
    Route::put('/fruitRating/{analysis_id}/{analysis_type}', [App\Http\Api\v1\AnalysisController::class, 'fruitRating']);
    Route::get('/getAnalyses', [App\Http\Api\v1\AnalysisController::class, 'getAnalyses']);
    Route::get('/getAppleAnalyses', [App\Http\Api\v1\AnalysisController::class, 'getAppleAnalyses']);
    Route::get('/getMaskedImage/{analysis_id}', [App\Http\Api\v1\AnalysisController::class, 'getMaskedImage']);
    Route::get('/getResults/{analysis_id}', [App\Http\Api\v1\AnalysisController::class, 'getResults']);
    Route::get('/getTraySummary/{analysis_id}', [App\Http\Api\v1\AnalysisController::class, 'getTraySummary']);
    Route::get('/getOriginalImage/{analysis_id}', [App\Http\Api\v1\AnalysisController::class, 'getOriginalImage']);
    Route::get('/getSegmentedImage/{analysis_id}/{segment_number}', [App\Http\Api\v1\AnalysisController::class, 'getSegmentedImage']);
    Route::get('/getSegmentedImages/{analysis_id}', [App\Http\Api\v1\AnalysisController::class, 'getSegmentedImages']);
    Route::get('/getRatingImage/{analysis_id}/{segment_number}', [App\Http\Api\v1\AnalysisController::class, 'getRatingImage']);
    Route::put('/flagSegmentedImage/{analysis_id}/{segment_number}', [App\Http\Api\v1\AnalysisController::class, 'flagSegmentedImage']);
    Route::get('/getSegmentedImageData/{analysis_id}/{segment_number}', [App\Http\Api\v1\AnalysisController::class, 'getSegmentedImageData']);
    Route::delete('/abortFromSegmentation/{analysis_id}', [App\Http\Api\v1\AnalysisController::class, 'abortFromSegmentation']);
    Route::delete('/abortFromFruitRating/{analysis_id}', [App\Http\Api\v1\AnalysisController::class, 'abortFromFruitRating']);
    Route::get('/getRatingImageData/{analysis_id}/{segment_number}', [App\Http\Api\v1\AnalysisController::class, 'getRatingImageData']);
    Route::get('/runAnalysis', [App\Http\Api\v1\AnalysisController::class, 'runAnalysis']);
});
