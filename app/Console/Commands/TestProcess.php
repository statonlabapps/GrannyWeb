<?php

namespace App\Console\Commands;

use App\Models\Analysis;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TestProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-segmentation {--analysisType=segmentation} {--model=pome_fruit-v1_0} {--input=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing Segmentation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $analysis = Analysis::create([
        //     'model' => $this->option('model'),
        //     'type' => $this->option('analysisType'),
        //     'input' => $this->option('input'),
        //     'title' => 'Test Process',
        //     'description' => 'This is a test process to see if we can run a command from the console.',
        //     'input_file' => 'test.txt',
        //     'output_file' => 'test_output.txt',
        // ]);
        
        $command = ['granny','-i','cli','--analysis',$this->option('analysisType'),'--model',$this->option('model'),'--input',$this->option('input')];
        $analysis->run($command);

    }
}
