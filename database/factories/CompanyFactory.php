<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Magenta ' . $this->faker->company ,
            'registration_number' => '21391283023',
            'contact_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'website' => 'www.magenta.com',
            'npwp' => '2312.232-123.23',
            'address' => $this->faker->address,
            'province' => $this->faker->state,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'logo' => 'magenta.png',
            'added_by' => 1,
        ];
    }
}
