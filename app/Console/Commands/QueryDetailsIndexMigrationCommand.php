<?php

namespace App\Console\Commands;

use App\Modules\DataMigration\Services\DataMigrationService;
use Illuminate\Console\Command;

class QueryDetailsIndexMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:index';

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
        //

        $migration = new DataMigrationService();
        $migration->queryDetailsIndexMigration();

    }
}
