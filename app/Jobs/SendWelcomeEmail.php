<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use JetBrains\PhpStorm\NoReturn;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable;

    public $timeout = 1; // Now we have sleep(3) in handle means it takes 3sec to complete  but we are failing it in one sec so like this you can give time out to job
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    #[NoReturn] public function handle(): void
    {
        sleep(3);
//        throw new \Exception();
//        dd('Sending welcome email');
    }
}
