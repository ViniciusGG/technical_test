<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class SimulateScheduledTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:scheduled-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate a scheduled transaction';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Simulating a scheduled transaction...');

        $currentTime = Carbon::now()->setTime(5, 0, 0);

        Carbon::setTestNow($currentTime);

        $this->info('Current time: ' . Carbon::now());

        $this->call('queue:work');

        Carbon::setTestNow(null);

        $this->call('queue:restart');

        $this->info('Scheduled transaction simulated');
    }
}
