<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $branchname = ['CB Main', 'CB Annex', 'CB Complex', 'CB Plus 1', 'CB Plus 2', 'CB Plus 3'];
        $status = ['Active', 'Inactive'];
        $atype = ['Administrator', 'Supervisor','Cashier','Leesee'];

        return [
            'remember_token' => Str::random(10),
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => Str::random(10),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'firstname' => Str::random(1),
            'middlename'=> Str::random(1),
            'lastname' => fake()->name(),
            'birthdate' => Carbon::create('2000', '01', '01'),
            'branchid' => '1',
            'branchname' => Arr::random($branchname),
            'accesstype' => Arr::random($atype),
            'status' => Arr::random($status),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
