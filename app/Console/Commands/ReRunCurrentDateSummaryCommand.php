<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSummary;
use App\Modules\JobHistory\Models\JobHistory;
use App\Modules\JobHistory\Services\JobHistoryService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReRunCurrentDateSummaryCommand extends Command
{
    /**
     * Crob job
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:current';

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

        $currentDate = Carbon::now('Australia/Sydney');

        // re-run current date within the current month
        JobHistoryService::createCurrentMonthlyJob($currentDate);

        // if the date is the first day of month, then re-run last whole month
        $firstOfMonth = $currentDate->copy()->firstOfMonth();
        if ($firstOfMonth->equalTo($currentDate)) {
            JobHistoryService::createCurrentMonthlyJob($firstOfMonth->subMonth());
        }
    }
}
