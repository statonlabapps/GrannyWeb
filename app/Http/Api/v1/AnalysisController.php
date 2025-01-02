<?php

namespace App\Http\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\MaskedImage;
use App\Models\SegmentedImage;
use App\Models\Results;
use App\Models\User;
use App\Models\TraySummary;
use App\Models\RatingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AnalysisController extends Controller
{
    public function create(Request $request)
    {
        info('reached api/analysisconrtoller!');
        Analysis::create($request->all());

//        Analysis::created();
    }
    public function newAnalysis(Request $request)
    {
        if($request->hasFile('image')){
            $image = $request->file('image');
            //save the name of the original image
            $originalImageName = $request->file('image')->getClientOriginalName();


            //remove spaces from custom id
            $customID = str_replace(' ', '_', $request->custom_id);

            //create file name based on custom id, to
            $imageName = $customID.'.'.$request->image->extension();

            //creates analysis with just the custom id, segmentation model, and the file name of the origianal image
            $analysis = Analysis::create([
                'custom_id' => $customID,
                'user_id' => $request->user_id,
                'user_name' => $request->user_name,
                'a1_model' => $request->a1_model,
                'original_image_type' => $request->image->extension(),
            ]);
            $analysis->save();

            //make a new directory and then use that directory as the input for segmentation
            File::makeDirectory(base_path().'/projects/project_'.$analysis->id.'/original_image', 0777, true);

            //save image to the new directory, under the new file name that is based on the custom id
            $request->image->move(base_path('/projects/project_'.$analysis->id.'/original_image'), $imageName);

            $analysis->original_image = $originalImageName;
            $analysis->original_image_path = base_path().'/projects/project_'.$analysis->id.'/original_image/'.$imageName;

            $analysis->a1_input = 'original_image';
            $analysis->save();

            //perform segmentation on the image recieved and return the segmented images
            $analysis->runSegmentation($analysis->id, $request->a1_model, $analysis->a1_input);

            return $analysis;
        };
    }
    public function fruitRating(Request $request, $analysis_id, $analysis_type)
    {
        $analysis = Analysis::where('id', $analysis_id)->first();
        $analysis->a2_type = $analysis_type;
        $analysis->save();

        $segmentedImages = SegmentedImage::where('analysis_id', $analysis_id)->get();
        //for each if segmented image is flagged, delete the file at the given path
        foreach ($segmentedImages as $image) {
            if($image->flagged){
                File::delete($image->file_path);
                $image->delete();
                $analysis->save();
            }
        }
        $timestamp = $analysis->segmentation_timestamp->format('Y-m-d-H-i');
        $analysis->a2_input = 'results/segmentation/'.$timestamp.'/segmented_images';
        $analysis->save();

        return $analysis->runFruitRating($analysis->id, $analysis_type, $analysis->a2_input);
    }
    public function getOriginalImage(Request $request, $analysis_id)
    {
        $analysis = Analysis::where('id', $analysis_id)->first();
        return response()->file($analysis->original_image_path);
    }
    public function getResults(Request $request, $analysis_id)
    {
        $results = Results::where('analysis_id', $analysis_id)->first();
        return response()->file($results->file_path);
    }

    public function getTraySummary(Request $request, $analysis_id)
    {
        $traySummary = TraySummary::where('analysis_id', $analysis_id)->first();
        return response()->file($traySummary->file_path);
    }
    public function getUserName(Request $request, $user_id)
    {
        $user = User::where('id', $user_id)->first();

        return $user->name;
    }
    //masked image controller
    public function getMaskedImage(Request $request, $analysis_id)
    {
        $maskedImage = MaskedImage::where('analysis_id', $analysis_id)->first();
        return response()->file($maskedImage->file_path);
    }
    public function getSegmentedImages(Request $request, $analysis_id)
    {
        $segmentedImages = SegmentedImage::where('analysis_id', $analysis_id)->get();

        return $segmentedImages;

    }
    public function getSegmentedImage(Request $request, $analysis_id, $segment_number)
    {
        $segmentedImage = SegmentedImage::where('analysis_id', $analysis_id)->where('segment_number', $segment_number)->first();
        if($segmentedImage){

            return response()->file($segmentedImage->file_path);
        }
        else{
            return null;
        }
    }
    public function getSegmentedImageData(Request $request, $analysis_id, $segment_number)
    {
        $segmentedImageData = SegmentedImage::where('analysis_id', $analysis_id)->where('segment_number', $segment_number)->first();
        if($segmentedImageData){

            return $segmentedImageData;
        }
        else{
            return null;
        }

    }
    //rating image controller
    public function getRatingImage(Request $request, $analysis_id, $segment_number)
    {
        $ratingImage = RatingImage::where('analysis_id', $analysis_id)->where('segment_number', $segment_number)->first();
        if($ratingImage){

            return response()->file($ratingImage->file_path);
        }
        else{
            return null;
        }
    }
    public function getRatingImageData(Request $request, $analysis_id, $segment_number)
    {
        $ratingImageData = RatingImage::where('analysis_id', $analysis_id)->where('segment_number', $segment_number)->first();
        if($ratingImageData){

            return $ratingImageData;
        }
        else{
            return null;
        }

    }
    public function flagSegmentedImage(Request $request, $analysis_id, $segment_number)
    {
        $segmentedImage = SegmentedImage::where('analysis_id', $analysis_id)->where('segment_number', $segment_number)->first();
        $segmentedImage->flagged = !$segmentedImage->flagged;
        $segmentedImage->save();
        return $segmentedImage;
    }



    public function deleteFile(Request $request, $analysis_id, $segment_number)
    {
        $analysis = Analysis::find($analysis_id);
        $segmentedImage = SegmentedImage::where('analysis_id', $analysis_id)->where('segment_number', $segment_number)->first();
        $segmentedImage->delete();
        $message = '';
        if(file_exists(base_path().'/projects/results/segmentation/'.$analysis->timestamp.'/segmented_images/'.$analysis->custom_id.'_fruit_'.$segment_number.'.png')){
            File::delete(base_path().'/projects/results/segmentation/'.$analysis->timestamp.'/segmented_images/'.$analysis->custom_id.'_fruit_'.$segment_number.'.png');
            $analysis->segmented_image_count = $analysis->segmented_image_count - 1;
            $analysis->save();
            $message = 'Success';
        }
        else{
            $message = 'Error. Try again.';
        }

        return $message;
    }
    public function getAnalysis(Request $request, $id)
    {
        $analysis = Analysis::where('id', $id)->first();
        $analysis->date = $analysis->segmentation_timestamp->format('m/d/y');
        $analysis->time = $analysis->segmentation_timestamp->format('H:i A');
        return $analysis;
    }

    //analyses
    public function getAnalyses(Request $request)
    {
        $analyses = Analysis::orderBy('id', 'desc')->get();
        $analyses->map(function ($analysis) {
            $analysis->date = $analysis->segmentation_timestamp->format('m/d/y');
            $analysis->time = $analysis->segmentation_timestamp->format('H:i');
        });
        return $analyses;
    }
    public function getAppleAnalyses(Request $request)
    {
        $analyses = Analysis::orderBy('id', 'desc')->where('a1_model','pome_fruit-v1_0' )->get();
        $analyses->map(function ($analysis) {
            $analysis->date = $analysis->segmentation_timestamp->format('m/d/y');
            $analysis->time = $analysis->segmentation_timestamp->format('H:i');
        });
        return $analyses;
    }
    public function runAnalysis(Request $request)
    {
        $analysis = Analysis::where('id', $request->id)->first();
        $analysis->run();
        return $analysis;
    }
    //function that will delete everything and bring you back to new analysis
    /*
        Must Delete

        analysis
        full masked image
        segmented images
        project_id folder
        results
        tray summary
        rating images
    */
    public function abortFromFruitRating(Request $request, $analysis_id)
    {
        // Find the analysis by ID
        $analysis = Analysis::find($analysis_id);

        // Check if the analysis exists before attempting to delete related items
        if ($analysis) {
            // Delete the associated masked image (if it exists)
            $maskedImage = MaskedImage::where('analysis_id', $analysis_id)->first();
            if ($maskedImage) {
                $maskedImage->delete();
            }

            // Delete the associated segmented images
            $segmentedImages = SegmentedImage::where('analysis_id', $analysis_id)->get();

            foreach ($segmentedImages as $segmentedImage) {
                $segmentedImage->delete();
            }

            $results = Results::where('analysis_id', $analysis_id)->first();

            if ($results) {
                $results->delete();
            }

            $traySummary = TraySummary::where('analysis_id', $analysis_id)->first();

            if ($traySummary) {
                $traySummary->delete();
            }

            $ratingImages = RatingImage::where('analysis_id', $analysis_id)->get();

            foreach ($ratingImages as $ratingImage) {
                $ratingImage->delete();
            }

            // Delete the analysis itself
            $analysis->delete();
        }

        if(File::exists(base_path().'/projects/project_'.$analysis_id)) {
            File::deleteDirectory(base_path().'/projects/project_'.$analysis_id);
        }

        return 'No whammies';
    }
    public function abortFromSegmentation(Request $request, $analysis_id)
    {
        // Find the analysis by ID
        $analysis = Analysis::find($analysis_id);

        // Check if the analysis exists before attempting to delete related items
        if ($analysis) {
            // Delete the associated masked image (if it exists)
            $maskedImage = MaskedImage::where('analysis_id', $analysis_id)->first();
            if ($maskedImage) {
                $maskedImage->delete();
            }

            // Delete the associated segmented images
            $segmentedImages = SegmentedImage::where('analysis_id', $analysis_id)->get();

            foreach ($segmentedImages as $segmentedImage) {
                $segmentedImage->delete();
            }

            // Delete the analysis itself
            $analysis->delete();
        }

        if(File::exists(base_path().'/projects/project_'.$analysis_id)) {
            File::deleteDirectory(base_path().'/projects/project_'.$analysis_id);
        }

        return 'No whammies';
    }
}
