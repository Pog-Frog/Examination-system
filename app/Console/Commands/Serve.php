<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Serve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the Laravel development server with 0.0.0.0';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        passthru('php artisan serve --host 0.0.0.0');
    }
}
