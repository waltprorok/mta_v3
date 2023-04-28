<?php

namespace App\Console\Commands\Log;

use Illuminate\Console\Command;

class ClearLogFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears out Laravel logs';

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
        $result = glob(base_path() . '/storage/logs/*.log');

        if (count($result)) {
            exec('truncate -s 0 ' . storage_path('logs/laravel.log')); // empties laravel log
            exec('rm ' . storage_path('logs/laravel-*.log')); // remove all logs with a date
        }
        
        $this->info('Logs have been cleared');

        return true;
    }
}
