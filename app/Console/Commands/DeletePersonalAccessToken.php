<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class DeletePersonalAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:personal-access-tokens';

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
        PersonalAccessToken::whereNotIn('tokenable_id', function ($query) {
                $query->select('id')->from('users');
            })
            ->delete();

        PersonalAccessToken::whereNotIn('tokenable_id', function ($query) {
                $query->select('id')->from('instructors');
            })
            ->delete();
    }
}
