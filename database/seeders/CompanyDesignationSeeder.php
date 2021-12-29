<?php

namespace Database\Seeders;

use App\Models\CompanyDesignation;
use Illuminate\Database\Seeder;

class CompanyDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyDesignation::factory()->count(3)->create();
    }
}
