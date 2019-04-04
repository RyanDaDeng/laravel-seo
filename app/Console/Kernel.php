<?php

namespace App\Console;

use App\Console\Commands\IndexIdMigrationCommand;
use App\Console\Commands\MigrateQueryProfileKeywordField;
use App\Console\Commands\PathMD5Command;
use App\Console\Commands\RankingMigrationCommand;
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
        PathMD5Command::class,
        MigrateQueryProfileKeywordField::class,
        RankingMigrationCommand::class,
        IndexIdMigrationCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
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
