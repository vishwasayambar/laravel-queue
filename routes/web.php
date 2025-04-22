<?php

use App\Http\Controllers\ProfileController;
use App\Jobs\PaymentProcess;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Foundation\Application;
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