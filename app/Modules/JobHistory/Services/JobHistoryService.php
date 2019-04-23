<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 15/4/19
 * Time: 4:18 PM
 */

namespace App\Modules\JobHistory\Services;


use App\Jobs\ProcessSummary;
use App\Modules\DataMigration\Services\SnapshotSummaryService;
use App\Modules\JobHistory\Models\JobHistory;
use Carbon\Carbon;

class JobHistoryService
{

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function runJobById($id)
    {
        $job = JobHistoryQuery::findJobById($id);
        $job->status = JobHistory::PENDING;
        $job->save();
        ProcessSummary::dispatch($job);
        return JobHistoryQuery::findJobById($job->id);
    }

    public function deleteJobById($id)
    {
        $job = JobHistoryQuery::findJobById($id);
        // drop table
        $service = new SnapshotSummaryService(Carbon::parse($job->date_from),Carbon::parse($job->date_to));
        $service->dropTableIfExists();
        // delete entry
        $job->delete();
    }
}