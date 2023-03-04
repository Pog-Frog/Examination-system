<?php

namespace App\Console\Commands;

use App\Models\Instructor;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DeleteProfilePics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:profile-pics';

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
        try {
            $user_path = "ProfilePics/students/";
            $instructor_path = "ProfilePics/instructors";

            Log::info("Public storage files cleared!");

            $userPics = File::files(public_path($user_path));
            $instructorPics = File::files(public_path($instructor_path));

            foreach ($userPics as $pic) {
                $picName = explode('/', $pic);
                $picName = end($picName);
                $user = User::where('photo', '=', $picName)->first();
                if (!$user) {
                    File::delete($pic);
                    Log::info("Deleted: " . $pic);
                } else {
                    Log::info("User exists: " . $pic);
                }
            }

            foreach ($instructorPics as $pic) {
                $picName = explode('/', $pic);
                $picName = end($picName);
                $instructor = Instructor::where('photo', '=',  $picName)->first();
                if (!$instructor) {
                    File::delete($pic);
                    Log::info("Deleted: " . $pic);
                } else {
                    Log::info("Instructor exists: " . $pic);
                }
            }

            $numberOfUserPics = count($userPics);
            $numberOfInstructorPics = count($instructorPics);

            Log::info("Number of user pics: " . $numberOfUserPics);
            Log::info("Number of instructor pics: " . $numberOfInstructorPics);

            $this->info('Cron command run successfully');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
