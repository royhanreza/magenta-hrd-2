<?php

namespace Database\Seeders;

use App\Models\CompanyDepartment;
use Illuminate\Database\Seeder;

class CompanyDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyDepartment::factory()->count(3)->create();
    }
}
