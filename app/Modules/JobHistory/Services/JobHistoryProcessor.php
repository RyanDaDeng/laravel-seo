<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 15/4/19
 * Time: 4:18 PM
 */

namespace App\Modules\JobHistory\Services;


use App\Modules\DataMigration\Services\SnapshotSummaryService;
use App\Modules\JobHistory\Models\JobHistory;
use App\Modules\Keywords\Services\KeywordQueries;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class JobHistoryProcessor
{
    /**
     * @var JobHistory $job
     */
    private $job;

    /**
     * Processing the job
     */
    private function inProcessing()
    {
        Log::alert(sprintf('Starting to run job #%s...', $this->job->id));
        $this->job->status = JobHistory::IN_PROCESSING;
        $this->job->save();
    }

    /**
     * Error happens
     */
    private function error()
    {
        Log::alert(sprintf('Error exception: #%s...', $this->job->id));
        $this->job->status = JobHistory::ERROR;
        $this->job->save();
    }


    /**
     * Finished job
     */
    private function finished()
    {
        Log::alert(sprintf('Job finished: #%s...', $this->job->id));
        $this->job->status = JobHistory::FINISHED;
        $this->job->save();
    }

    /**
     * @param JobHistory $job
     */
    public function runJob(JobHistory $job)
    {
        // if the job is in processing
        if ($job->status === JobHistory::IN_PROCESSING) {
            return;
        }

        $this->job = $job;
        $this->inProcessing();
        try {
            // create table
            $dateFrom = Carbon::parse($this->job->date_from);
            $dateTo = Carbon::parse($this->job->date_to);
            $snapShotSummary = new SnapshotSummaryService($dateFrom, $dateTo);
            $snapShotSummary->dropTableIfExists();
            $snapShotSummary->createTable();

            $result = KeywordQueries::summarize($dateFrom, $dateTo);
            $snapShotSummary->insertData($result);

            $this->finished();
        } catch (\Exception $e) {
            Log::error($e);
            $this->error();
        }
        broadcast(new \App\Events\ProcessSummaryJobCompleted($this->job));
    }
}