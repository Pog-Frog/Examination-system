<?php

namespace App\Console\Commands;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Console\Command;

class DeleteUnverifiedAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unverified-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::where('email_verified_at', null)
            ->delete();

        Instructor::where('email_verified_at', null)
            ->delete();
    }
}
