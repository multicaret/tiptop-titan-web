<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userName = $this->faker->userName;

        return [
            'first' => $this->faker->firstName,
            'last' => $this->faker->lastName,
            'username' => 'db-seeder-test-'.$userName,
            'email' => strtolower($userName).'@multicaret.com',
            'email_verified_at' => now(),
            'language_id' => config('defaults.language.id'),
            'country_id' => config('defaults.country.id'),
            'region_id' => config('defaults.region.id'),
            'city_id' => config('defaults.city.id'),
            'currency_id' => config('defaults.currency.id'),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
