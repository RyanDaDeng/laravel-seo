<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 15/4/19
 * Time: 3:37 PM
 */

namespace App\Modules\JobHistory\Controllers\Web;


use App\Modules\JobHistory\Models\JobHistory;
use App\Modules\JobHistory\Services\JobHistoryQuery;
use App\Modules\JobHistory\Services\JobHistoryService;
use Illuminate\Http\Request;

class JobHistoryWebController
{

    public function getJobHistories(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $status = $request->query('status');


        $query = JobHistory::query();


        if ($dateFrom && $dateTo) {
            $query = $query->where('date_from', '<=', $dateFrom)
                ->where('date_to', '>=', $dateTo);
        } else if ($dateFrom) {
            $query = $query->where('date_from', '=', $dateFrom);
        } else if ($dateTo) {
            $query = $query->where('date_to', '>=', $dateTo);
        }

        if ($status !== null) {
            $query = $query->where('status', $status);
        }

        return $query->orderBy('id', 'desc')->paginate(20);
    }


    public function rerunJobById(Request $request, $id, JobHistoryService $jobHistoryService)
    {
        return $jobHistoryService->runJobById($id);
    }


    public function deleteJobById(Request $request, $id, JobHistoryService $jobHistoryService)
    {
        $jobHistoryService->deleteJobById($id);

    }
}