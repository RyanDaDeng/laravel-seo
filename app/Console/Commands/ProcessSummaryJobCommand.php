<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSummary;
use App\Modules\JobHistory\Models\JobHistory;
use Illuminate\Console\Command;

class ProcessSummaryJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:summary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $processor = new JobHistoryProcessor();
        $jobs = JobHistory::query()->where('status',JobHistory::PENDING)->get();
        foreach($jobs as $job){
//           $processor->runJob($job);
            ProcessSummary::dispatch($job);
        }
    }
}
