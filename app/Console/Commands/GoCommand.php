<?php

namespace App\Console\Commands;

use App\Enums\Param\ParamFilterTypeEnum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:go';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public static function handle()
    {
        //dd(ParamFilterTypeEnum::map());
        return Log::info('Scheduler is working!');

    }
}
