<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use JetBrains\PhpStorm\NoReturn;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable;

//    public int $timeout = 1; // Now we have sleep(3) in handle means it takes 3sec to complete  but we are failing it in one sec so like this you can give time out to job

    public int $tries = -1; // this is used how many times to retry if it get failed, if you set it -1 then it retry infinite times
    //You can define one function here called retryUntil() which returns any condition(Ex: time) to till it gets retry

    public int $backoff = 2; // This will add 2 min delay in between try new try like when 1st time fail then it take delay of 2 sec then again retry

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
        throw new \Exception('Failed');
//        sleep(3);
//        dd('Sending welcome email');
    }


//    Till this condition get true it will retries
    public function retryUntil(): \Illuminate\Support\Carbon
    {
        return now()->addMinutes(1);
    }
}
