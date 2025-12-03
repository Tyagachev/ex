<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEmailsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class ProcessEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process incoming support emails';

    public function handle()
    {
        //ProcessEmailsJob::dispatch();
        Log::info('Process');
    }
}
