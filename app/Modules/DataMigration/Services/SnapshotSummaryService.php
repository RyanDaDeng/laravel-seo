<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 29/3/19
 * Time: 9:36 AM
 */

namespace App\Modules\DataMigration\Services;


use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryProfile;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class SnapshotSummaryService
{

    const TABLE_PREFIX = 'tbl_gw_summary_';
    public $fromDate;
    public $toDate;
    public $tableName;

    /**
     * SnapshotSummaryService constructor.
     * @param Carbon $fromDate
     * @param Carbon $toDate
     */
    public function __construct(Carbon $fromDate, Carbon $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->tableName = $this->generateTableName();
    }


    public static function instance(Carbon $fromDate, Carbon $toDate)
    {
        return new self ($fromDate, $toDate);
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     * @return string
     */
    public static function getTableNameByString($dateFrom, $dateTo)
    {
        return self::TABLE_PREFIX . $dateFrom . '_' . $dateTo;
    }


    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return string
     */
    public static function getTableName(Carbon $dateFrom, Carbon $dateTo)
    {
        return self::TABLE_PREFIX . $dateFrom->format('Y_m_d') . '_' . $dateTo->format('Y_m_d');
    }


    /**
     * @param Carbon $date
     * @return string
     */
    public static function getTableNameByMonthlyRange(Carbon $date)
    {
        return self::TABLE_PREFIX . $date->copy()->startOfMonth()->format('Y_m_d') . '_' . $date->copy()->endOfMonth()->format('Y_m_d');
    }

    /**
     * @return bool
     */
    public function checkTableExists()
    {
        return self::checkIfDateRangeExists($this->fromDate, $this->toDate);
    }

    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return bool
     */
    public static function checkIfDateRangeExists(Carbon $dateFrom, Carbon $dateTo)
    {
        return self::hasTable(self::getTableName($dateFrom, $dateTo));
    }

    /**
     * Generate table name
     */
    public function generateTableName()
    {
        return $this->getTableName($this->fromDate, $this->toDate);

    }

    /**
     * Check if table exists
     * @param $tableName
     * @return bool
     */
    public static function hasTable($tableName)
    {
        return \Schema::hasTable($tableName);
    }

    /**
     * Create a new table
     */
    public function createTable()
    {
        if (!$this->hasTable($this->tableName)) {
            \Schema::create($this->tableName, function (Blueprint $table) {
                $table->increments('id');
                $table->date('to_date');
                $table->date('from_date');
                $table->integer('page');
                $table->integer('keyword');
                $table->integer('sum_clicks');
                $table->integer('sum_impressions');
                $table->integer('device');
                $table->integer('sum_positions');
                $table->float('sum_average_weight_ranking');
                $table->float('avg_ctr');
                $table->float('avg_positions');
                $table->timestamps();
                $table->index(['to_date', 'device', 'page', 'keyword'], 'to_date_page_keyword_device_index');
                $table->index(['page', 'keyword', 'device'], 'page_keyword_device_index');
            });
        }
    }

    /**
     * Drop the existing table
     */
    public function dropTableIfExists()
    {
        \Schema::dropIfExists($this->tableName);
    }

    /**
     * Insert new data
     * @param $result
     */
    public function insertData($result)
    {
        $now = Carbon::now()->format('Y-m-d');
        foreach ($result as $datum) {
            $avgPosition = round($datum['sum_average_weight_ranking'] / $datum['sum_impressions'], 4);


            DB::table($this->tableName)->insert(
                [
                    'from_date' => $this->fromDate->format('Y-m-d H:i:s'),
                    'to_date' => $this->toDate->format('Y-m-d H:i:s'),
                    'device' => $datum['device_type'],
                    'page' => $datum['page_id'],
                    'keyword' => $datum['keyword_id'],
                    'avg_positions' => $avgPosition,
                    'avg_ctr' => $datum['avg_ctr'],
                    'sum_average_weight_ranking' => $datum['sum_average_weight_ranking'],
                    'sum_positions' => $datum['sum_positions'],
                    'sum_impressions' => $datum['sum_impressions'],
                    'sum_clicks' => $datum['sum_clicks'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            );
        }
    }

    /**
     * truncate table
     */
    public function truncateTable()
    {
        if ($this->hasTable($this->tableName)) {
            DB::table($this->tableName)->truncate();
        }
    }


}