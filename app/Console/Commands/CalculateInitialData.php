<?php

namespace App\Console\Commands;

use App\Modules\DataMigration\Services\CtrBenchmarkService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateInitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:initial {from : Y-m-d date_from to calculate} {to : Y-m-d date_to to calculate} {--reset= : 1 delete all previous calculated data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Step 1';

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
        $reCal = $this->option('reset');
        if ($reCal == '1') {
            $reCal = true;
        } else {
            $reCal = false;
        }

        $dateFrom = Carbon::parse($this->argument('from'));
        $dateTo = Carbon::parse($this->argument('to'));

        if (!$this->confirm("Are you sure you want to calculate data between $dateFrom and $dateTo")) {
            return;
        }

        if (!($reCal = true && $this->confirm("Are you sure you want to recalculate everything again?"))) {
            return;
        }

        $service = new CtrBenchmarkService();
        $service->calculateInitial(Carbon::parse($this->argument('from')), Carbon::parse($this->argument('to')), $reCal);
    }
}
