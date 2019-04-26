<?php

namespace App\Console;

use App\Console\Commands\CalculateSnapshot;
use App\Console\Commands\CtrBenchmark;
use App\Console\Commands\CalculateInitialData;
use App\Console\Commands\PathMD5Command;
use App\Console\Commands\ProcessSummaryJobCommand;
use App\Console\Commands\ReRunCurrentDateSummaryCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        ProcessSummaryJobCommand::class,
        PathMD5Command::class,
        CtrBenchmark::class,
        CalculateInitialData::class,
        ReRunCurrentDateSummaryCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('process:current')
            ->timezone('Australia/Sydney')
            ->dailyAt('23:59')->evenInMaintenanceMode();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
