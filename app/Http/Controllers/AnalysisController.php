<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\User;


use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function segmentation(Request $request, $analysis_id)
    {
        return Inertia::render('SegmentationPage', [
            'analysis_id' => $analysis_id,
        ]);
    }
    
    public function fruitRating(Request $request, $analysis_id)
    {
        return Inertia::render('FruitRatingPage', [
            'analysis_id' => $analysis_id,
        ]);
    }
    public function summary(Request $request, $analysis_id)
    {
        return Inertia::render('SummaryPage', [
            'analysis_id' => $analysis_id,
        ]);
    }

}
