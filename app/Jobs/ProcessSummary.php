<?php

namespace App\Jobs;

use App\Modules\JobHistory\Models\JobHistory;
use App\Modules\JobHistory\Services\JobHistoryProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobHistory;

    /**
     * ProcessSummary constructor.
     * @param JobHistory $jobHistory
     */
    public function __construct(JobHistory $jobHistory)
    {
        //
        $this->jobHistory = $jobHistory;
    }

    /**
     * @param JobHistoryProcessor $jobHistoryProcessor
     */
    public function handle(JobHistoryProcessor $jobHistoryProcessor)
    {
        $jobHistoryProcessor->runJob($this->jobHistory);
    }
}
