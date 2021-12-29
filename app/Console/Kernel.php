<?php

namespace App\Console;

use App\Console\Commands\ResetLeave;
use App\Models\Company;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\ResetLeave',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(function () {
        //     // Log::debug('asdhkajshdkjashd');
        //     $company = new Company;
        //     $company->name = 'asdasdasd';
        //     $company->registration_number = 'asdasdasd';
        //     $company->contact_number = 'asdasdasd';
        //     $company->email = 'dfgdfgdfgdfg';
        //     $company->website = 'jhgjghj';
        //     $company->npwp = 'asdasdasd';
        //     $company->address = 'asdaljkljksdasd';
        //     $company->province = 'asdasds';
        //     $company->country = 'asddsgdfgdfgfg';
        //     $company->city = '097-5506jikhgfh';
        //     $company->zip_code = 'dshfjkhkjashd';
        //     // $company->logo = $request->logo;
        //     // $company->added_by = $request->added_by;
        //     $company->logo = 'magenta-logo.png';
        //     $company->added_by = 1;

        //     $company->save();

        // })->everyTwoMinutes();
        $schedule->command('leave:reset')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
