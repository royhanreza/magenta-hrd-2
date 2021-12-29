<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\LeaveSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all create and create new leaves';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $setting = LeaveSetting::first();

        if ($setting == null) {
            return;
        }

        // Log::info($setting);
        // $company = new Company;
        // $company->name = 'asdasdasd';
        // $company->registration_number = 'asdasdasd';
        // $company->contact_number = 'asdasdasd';
        // $company->email = 'dfgdfgdfgdfg';
        // $company->website = 'jhgjghj';
        // $company->npwp = 'asdasdasd';
        // $company->address = 'asdaljkljksdasd';
        // $company->province = 'asdasds';
        // $company->country = 'asddsgdfgdfgfg';
        // $company->city = '097-5506jikhgfh';
        // $company->zip_code = 'dshfjkhkjashd';
        // $company->logo = 'magenta-logo.png';
        // $company->added_by = 1;

        // $company->save();
    }
}
