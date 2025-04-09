<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use JetBrains\PhpStorm\NoReturn;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable;

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
        throw new \Exception();
//        dd('Sending welcome email');
    }
}
