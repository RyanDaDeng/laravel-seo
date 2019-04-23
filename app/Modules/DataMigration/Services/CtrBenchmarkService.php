<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 8/4/19
 * Time: 10:56 AM
 */

namespace App\Modules\DataMigration\Services;


use App\Modules\Keywords\Models\QueryProfile;
use App\Modules\Keywords\Services\KeywordQueries;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CtrBenchmarkService
{


    /**
     * Reset initial avg position and ctr value as 0
     */
    public function resetInitial()
    {
        // drop column
        DB::query()->selectRaw('SET SQL_SAFE_UPDATES = 0;');
        QueryProfile::query()->where('initial_avg_position', '!=', 0)->update(['initial_avg_position' => 0]);
        QueryProfile::query()->where('initial_ctr_value', '!=', 0)->update(['initial_ctr_value' => 0]);
        QueryProfile::query()->where('initial_impressions', '!=', 0)->update(['initial_impressions' => 0]);
        DB::query()->selectRaw('SET SQL_SAFE_UPDATES = 1;');
    }


    /**
     * calculate initial avg position and ctr value and impressions for each profile
     * @param bool $reCal
     */
    public function calculateInitial($reCal = false)
    {
        if ($reCal === true) {
            $this->resetInitial();
        }

        $from = Carbon::parse('2019-03-04')->subDay(28);
        $to = Carbon::parse('2019-03-04');
        $result = KeywordQueries::getMonthlyBaseQueryForCtr($from, $to)->get()->toArray();

        foreach ($result as $datum) {
            $avgPosition = round($datum->sum_average_weight_ranking / $datum->sum_impressions, 4);
            QueryProfile::query()->updateOrInsert(
                [
                    'page' => $datum->page_id,
                    'keyword' => $datum->keyword_id
                ],
                [

                    'page' => $datum->page_id,
                    'keyword' => $datum->keyword_id,
                    'initial_avg_position' => $avgPosition,
                    'initial_ctr_value' => $datum->avg_ctr,
                    'initial_impressions' => $datum->sum_impressions
                ]
            );
        }
    }


    /**
     * Calculate benchmark
     */
    public function calculateCtrBenchmark()
    {

        // calculate average ctr value for the same position value
        $data = QueryProfile::query()->selectRaw('initial_avg_position, round(sum(initial_ctr_value*100)/count(*),1)  as benchmark')
            ->groupBy('initial_avg_position')->get();
        foreach ($data as $datum) {
            DB::query()->selectRaw('SET SQL_SAFE_UPDATES = 0;');
            QueryProfile::query()->where('initial_avg_position', '=', $datum->initial_avg_position)
                ->update(['ctr_benchmark' => $datum->benchmark]);
            DB::query()->selectRaw('SET SQL_SAFE_UPDATES = 1;');
        }

    }


    /**
     * Calculate click potential
     */
    public function calculateClickPotential()
    {
        // use the initial avg position to calculate click potential value
        DB::query()->selectRaw('SET SQL_SAFE_UPDATES = 0;');
        QueryProfile::query()->where('id', '>', 0)
            ->update(['click_potential' => DB::raw('initial_impressions* (ctr_benchmark/100)')]);
        DB::query()->selectRaw('SET SQL_SAFE_UPDATES = 1;');
    }
}