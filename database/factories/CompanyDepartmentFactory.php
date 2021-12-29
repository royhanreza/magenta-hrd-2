<?php

namespace Database\Factories;

use App\Models\CompanyDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyDepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyDepartment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
            'company_id' => 1,
            'employee_id' => 1,
            'company_location_id' => 1,
            'added_by' => 1,
        ];
    }
}
