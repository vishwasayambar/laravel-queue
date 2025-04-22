<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateReport implements ShouldQueue
{
    use Queueable;

    /*Each Failed job get add in failed_job table Each job has unique id if tries gets complete and though also job get failed
    then it gets add in failed_job table then we can run that job manually also by command with using unique ID
    php artisan queue:retry f1a5eb0c-821f-48ae-b17d-b291bc922625
     * */

    public int $tries = 5;
//    public int $maxExceptions = 2; // this is stop the retries even tries is not completed all like we have 5 $tries
    // and this is 2 then when it through 2 Exception then it get failed direct it will not get retry again


    public array $backoff = [10, 20, 40]; // this is add delay in retrying the job on failed it take single value as in sec or array
//    array works like when we have 10. tries then for 1st try it take 10 for 2nd 20 and so on but if tries is more than in delay specify in array then it take last elememnt for all remaining

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        sleep(1);
//        $this->release(4); // This is used within a job class to release the job back into the queue after it has failed or is being retried.
        /*Functionality: When you call $this->release(4);, you are instructing the queue worker to release the job back into the queue after 4 seconds. This means that the job will be available for retry after that delay. If you don’t provide any argument, it defaults to the behavior specified in your queue configuration.
         This is commonly used in cases where the job failed due to some temporary issue, like a network timeout, and you want to delay the retry attempt instead of immediately processing it again.
         * */
        throw new \Exception('Failed');
        return;
    }

}
