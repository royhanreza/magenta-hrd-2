<?php

namespace Database\Factories;

use App\Models\CompanyDesignation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyDesignationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyDesignation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
            'department_id' => 1,
            'description' => 'lorem ipsum',
            'added_by' => 1,
        ];
    }
}
