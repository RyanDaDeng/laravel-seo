<?php

namespace App\Console\Commands;

use App\Modules\DataMigration\Services\CtrBenchmarkService;
use Illuminate\Console\Command;

class CtrBenchmark extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:benchmark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Step 2';

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
        $service = new CtrBenchmarkService();
        $service->calculateCtrBenchmark();
    }
}
