<?php

namespace App\Console\Commands;

use App\Modules\DataMigration\Services\CtrBenchmarkService;
use Illuminate\Console\Command;

class CalculateInitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:initial {--re=}';

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

        $reCal = $this->option('re');


        if($reCal == '1'){
            $reCal = true;
        }else{
            $reCal = false;
        }

        $service = new CtrBenchmarkService();
        $service->calculateInitial($reCal);
    }
}
