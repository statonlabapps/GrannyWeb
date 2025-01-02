<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FruitRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fruit-rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Fruit Rating';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $command = ['granny','-i','cli','--analysis','starch','--input','results/segmentation/2024-11-08-10-13/segmented_images'];
       $process = new Process($command);
       $process->setWorkingDirectory($process->getWorkingDirectory().'/test_runs');
       
       
       $process->run();
    
    
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            echo $process->getErrorOutput();
            throw new ProcessFailedException($process);
        }
    
        $output = $process->getOutput();
        echo $output;
    
       
    }
}
