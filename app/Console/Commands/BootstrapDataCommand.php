<?php

namespace App\Console\Commands;

use App\Jobs\Deputy\SyncAllDeputiesJob;
use App\Jobs\DeputyExpense\SyncAllDeputiesExpensesJob;
use Illuminate\Console\Command;

class BootstrapDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bootstrap-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bootstrapping deputies…');

        SyncAllDeputiesJob::dispatchSync();

        $this->info('Bootstrapping expenses…');

        SyncAllDeputiesExpensesJob::dispatchSync();

        $this->info('Done.');
    }
}
