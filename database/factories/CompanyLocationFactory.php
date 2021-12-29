<?php

namespace Database\Factories;

use App\Models\CompanyLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => 1,
            'employee_id' => 1,
            'location_name' => $this->faker->streetName,
            'contact_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'npwp' => '232323123',
            'address' => $this->faker->address,
            'province' => $this->faker->state,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'added_by' => 1,
        ];
    }
}
