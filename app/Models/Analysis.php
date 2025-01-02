<?php

namespace App\Models;

use Database\Factories\AnalysisFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Models\Analysis;
use App\Models\MaskedImage;
use App\Models\SegmentedImage;
use App\Models\RatingImage;
use App\Models\Results;
use App\Models\TraySummary;
use Illuminate\Support\Facades\File; 

class Analysis extends Model
{
    /** @use HasFactory<AnalysisFactory> */
    use HasFactory;

    protected $table = 'analyses';

    protected $fillable = [
        'custom_id', // user-facing custom id. they can customize this if they wish, otherwise it will be generated for them
        'user_id', // user who created the analysis
        'user_name',

        'team_id',
        
        'a1_model',
        'a1_input', //directory that holds the images  

        'a2_type',
        'a2_input', //directory that holds the images

        'title', // a user-generated title for this run
        'description', // an optional user-generated description of the run
        
        'original_image', 
        'original_image_path', 
        'original_image_type', 

        'rating_timestamp', //for accessing relevant files

        'segmented_image_count', //for accessing relevant files
        'rating_image_count', //for accessing relevant files
        'segmentation_output', //for accessing relevant files
    ];
protected $casts = [
    'segmentation_timestamp' => 'datetime:Y-m-d-H-i',
];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function runSegmentation($id, $model, $input)
    {
        $command = ['granny','-i','cli','--analysis','segmentation','--model',$model,'--input',$input];
        
        // need to make async later
        $process = new Process($command);
        
        
        $analysis = Analysis::find($id);
        $process->setWorkingDirectory(base_path().'/projects/project_'.$analysis->id);
        
        date_default_timezone_set("America/New_York");
        $dateTime = date("Y-m-d-H-i");

        //update analysis to include time stamp
        $analysis->segmentation_timestamp = $dateTime;

        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            echo $process->getErrorOutput();
            throw new ProcessFailedException($process);
        }

        

        $segmentedImageCount = 0;
        for($x = 1; $x < 25; $x++){
            if(file_exists(base_path().'/projects/project_'.$analysis->id.'/results/segmentation/'.$dateTime.'/segmented_images/'.$analysis->custom_id.'_fruit_'.$x.'.png')){
                $segmentedImage = SegmentedImage::create([
                    'analysis_id' => $analysis->id,
                    'file_path' =>base_path().'/projects/project_'.$analysis->id.'/results/segmentation/'.$dateTime.'/segmented_images/'.$analysis->custom_id.'_fruit_'.$x.'.png',
                    'name' => $analysis->custom_id.'_fruit_'.$x.'.png',
                    'segment_number' => $x,
                    'flagged' => false
                ]);
                $segmentedImage->save();
                $segmentedImageCount++;
            }
        } 
        $analysis->segmented_image_count = $segmentedImageCount;
        
        $analysis->save();
        if(file_exists(base_path().'/projects/project_'.$analysis->id.'/results/segmentation/'.$dateTime.'/full_masked_images/'.$analysis->custom_id.'_masked_image.png')){

            //add masked image to db
            $maskedImage = MaskedImage::create([
                'analysis_id' => $analysis->id,
                'file_path' =>base_path().'/projects/project_'.$analysis->id.'/results/segmentation/'.$dateTime.'/full_masked_images/'.$analysis->custom_id.'_masked_image.png',
            ]);
            $maskedImage->save();
        }
        
        return $analysis;
    }
    public function runFruitRating($analysis_id, $analysis_type, $input)
    {
        $command = ['granny','-i','cli','--analysis',$analysis_type,'--input',$input];

        // need to make async later
        $process = new Process($command);
        
        
        
        $analysis = Analysis::find($analysis_id);
        $process->setWorkingDirectory(base_path().'/projects/project_'.$analysis->id);
        
        date_default_timezone_set("America/New_York");
        $dateTime = date("Y-m-d-H-i");
        
        //update analysis to include time stamp
        $analysis->rating_timestamp = $dateTime;
        $analysis->save();


        $process->run();
        
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            echo $process->getErrorOutput();
            throw new ProcessFailedException($process);
        }
        $ratingImageCount = 0;

        for($x = 1; $x <= $analysis->segmented_image_count; $x++){
            //not the correct timestamp
            if(file_exists(base_path().'/projects/project_'.$analysis->id.'/results/'.$analysis_type.'/'.$analysis->rating_timestamp.'/'.$analysis->custom_id.'_fruit_'.$x.'.png')){
                $ratingImage = RatingImage::create([
                    'analysis_id' => $analysis->id,
                    'file_path' =>base_path().'/projects/project_'.$analysis->id.'/results/'.$analysis_type.'/'.$analysis->rating_timestamp.'/'.$analysis->custom_id.'_fruit_'.$x.'.png',
                    'name' => $analysis->custom_id.'_fruit_'.$x.'.png',
                    'segment_number' => $x,
                ]);
                $ratingImage->save();
                $ratingImageCount++;
            }
            $analysis->rating_image_count = $ratingImageCount;
            $analysis->save();

        } 

        if(file_exists(base_path().'/projects/project_'.$analysis->id.'/results/'.$analysis_type.'/'.$analysis->rating_timestamp.'/results.csv')){
            $results = Results::create([
                'analysis_id' => $analysis->id,
                'file_path' =>base_path().'/projects/project_'.$analysis->id.'/results/'.$analysis_type.'/'.$analysis->rating_timestamp.'/results.csv',
            ]);
            $results->save();
        }

        if(file_exists(base_path().'/projects/project_'.$analysis->id.'/results/'.$analysis_type.'/'.$analysis->rating_timestamp.'/tray_summary.csv')){
            $traySummary = TraySummary::create([
                'analysis_id' => $analysis->id,
                'file_path' =>base_path().'/projects/project_'.$analysis->id.'/results/'.$analysis_type.'/'.$analysis->rating_timestamp.'/tray_summary.csv',
            ]);
            $traySummary->save();

        }

        return $analysis;
    }
}
