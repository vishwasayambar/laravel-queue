<?php

use App\Http\Controllers\ProfileController;
use App\Jobs\Deploy;
use App\Jobs\GenerateReport;
use App\Jobs\PaymentProcess;
use App\Jobs\PullRepo;
use App\Jobs\RunTests;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('testJob', function () {
//    (new SendWelcomeEmail())->handle(); //Without Queue
//        SendWelcomeEmail::dispatch()->delay(now()->addMinutes(5)); // This will Delay the processing of job by worker for 5 min even worker is there it will not process till 5 min
//    SendWelcomeEmail::dispatch();


//    Now suppose we have lots of job to process like 100 and each job taking long time to process but you have to some imp Job which you want to run on priority before that current running jobs
    // like queue work as FIFO then it will not allow to run which come last to run first like below Example
    foreach (range(1, 100) as $i) {
        SendWelcomeEmail::dispatch();
    }

//    Now above it taking more time to run but below is Payment job it should run first then how then there is queue priority comes
//Solution One = Run multiple workers like two separate tabs run php artisan queue:work so it will complete fast but
// Solution 2 = High priority task run on separate queue and while running worker there is method option to set queue priority
//like php artisan queue:work --queue=payment,default like this now jobs in payment queue will run fast even default have job pending to run
//bydefault it runs on default queue if we not provide such options
        PaymentProcess::dispatch()->onQueue('payment');
//        dd('Added In Queue');
});

//Handling Attempts & Failures
Route::get('generateReport', function () {
    GenerateReport::dispatch();
});

//Dispatching Workflows
Route::get('dispatchingWorkflows', function () {
    /* CHAIN
    This all jobs run synchronously as one failed further get stopped execution
     * */
    $chain = [
        new PullRepo(),
        new RunTests(),
        new Deploy(),
    ];
//    Bus::chain($chain)->dispatch();

    /* BATCH
    This all jobs run parallel not depend on each other here we can start multiple workers to run
    them parallel also if one of its get failed it will not stop but it mark as total batch as failed/cancelled even some jobs get run
    NOTE: ->allowFailures() will avoid to mark cancel whole batch on failed one of its job, as it mark as canceled it again add in queue to run again

    Now to retry the failed job of that batch then run  php artisan queue:retry-batch  9ebbb7bf-d53b-45b2-ae59-f6a37f761ac1 -> BatchId
    then it will move that failed jobs again in queue for run
     * */

    $batch = [
        new PullRepo(),
        new PullRepo(),
        new RunTests(),
        new Deploy(),
    ];
    Bus::batch($batch)
        ->allowFailures()
        ->dispatch();
});