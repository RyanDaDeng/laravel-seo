<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 15/4/19
 * Time: 4:18 PM
 */

namespace App\Modules\JobHistory\Services;

use App\Jobs\ProcessSummary;
use App\Modules\JobHistory\Models\JobHistory;
use Carbon\Carbon;

class JobHistoryQuery
{

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public static function findJobById($id)
    {
        return JobHistory::query()->findOrFail($id);
    }

    /**
     * @param Carbon $date
     * @return JobHistory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public static function createCurrentMonthlyJob(Carbon $date)
    {
        $dateFrom = $date->copy()->startOfMonth()->format('Y-m-d');
        $dateTo = $date->copy()->endOfMonth()->format('Y-m-d');
        $obj = JobHistory::query()
            ->where(
                'date_from', $dateFrom
            )->where(
                'date_to', $dateTo
            )->first();

        if (!$obj) {
            // create a new job
            $obj = new JobHistory();
            $obj->date_from = $dateFrom;
            $obj->date_to = $dateTo;
            $obj->save();
        } else {
            $obj->status = JobHistory::PENDING;
            $obj->save();
        }
        ProcessSummary::dispatch($obj)->onQueue('high');
        return $obj;
    }


    /**
     * @param $dateFrom
     * @param $dateTo
     * @return JobHistory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public static function createOrGetByDateRange(Carbon $dateFrom, Carbon $dateTo)
    {
        $obj = JobHistory::query()
            ->where(
                'date_from', $dateFrom->format('Y-m-d')
            )->where(
                'date_to', $dateTo->format('Y-m-d')
            )->first();

        if (!$obj) {
            // create a new job
            $obj = new JobHistory();
            $obj->date_from = $dateFrom->format('Y-m-d');
            $obj->date_to = $dateTo->format('Y-m-d');
            $obj->save();
            ProcessSummary::dispatch($obj)->onQueue('high');
        }
        return $obj;
    }

    /**
     * @param Carbon $dateRangeFrom
     * @param Carbon $dateTo
     * @return bool
     */
    public static function validateJobDateRange($dateRangeFrom, $dateTo)
    {
        $obj = self::createOrGetByDateRange($dateRangeFrom, $dateTo);
        return $obj->status === JobHistory::FINISHED;
    }

    /**
     * Create job history on monthly basis
     * @param Carbon $dateRangeFrom
     * @param Carbon $dateTo
     * @return bool
     */
    public static function createAndValidateDateRangeOnMonthly(Carbon $dateRangeFrom, Carbon $dateTo)
    {
        $startOfFromMonth = $dateRangeFrom->startOfMonth();
        $startOfToMonth = $dateTo->startOfMonth();

        $isReady = true;
        while ($startOfFromMonth <= $startOfToMonth) {
            $obj = self::createOrGetByDateRange($startOfFromMonth, $startOfFromMonth->copy()->endOfMonth());
            if ($obj->status !== JobHistory::FINISHED) {
                $isReady = false;
            }
            $startOfFromMonth->addMonth();
        }
        return $isReady;
    }


    public static function createAndValidateCustomDateRange(Carbon $dateFrom, Carbon $dateTo)
    {
        $dateRange = self::getDateRangeArray($dateFrom, $dateTo);
        $isReady = true;
        foreach ($dateRange as $range) {
            $obj = self::createOrGetByDateRange($range['start'], $range['end']);
            if ($obj->status !== JobHistory::FINISHED) {
                $isReady = false;
            }
        }
        return $isReady;
    }


    public static function getDateRangeArray(Carbon $dateFrom, Carbon $dateTo)
    {
        $res = [];
        $start = $dateFrom->copy();
        while ($start <= $dateTo) {

            // base condition: if the current month, then just return
            if ($start->year === $dateTo->year && $start->month === $dateTo->month) {
                $row = [
                    'start' => $start->copy(),
                    'end' => $dateTo,
                ];
                $res[] = $row;
                break;
            }
            $row = [
                'start' => $start->copy(),
                'end' => $start->copy()->endOfMonth()
            ];
            $res[] = $row;
            $start->startOfMonth()->addMonth();
        }
        return $res;
    }


}