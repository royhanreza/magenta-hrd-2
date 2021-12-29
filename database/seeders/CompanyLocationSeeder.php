<?php

namespace Database\Seeders;

use App\Models\CompanyLocation;
use Illuminate\Database\Seeder;

class CompanyLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyLocation::factory()->count(3)->create();
    }
}
