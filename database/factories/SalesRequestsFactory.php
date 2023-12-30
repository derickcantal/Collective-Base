<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SalesRequestsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $branchname = ['CB Main', 'CB Annex', 'CB Complex', 'CB Plus 1', 'CB Plus 2', 'CB Plus 3'];
        $rpamount = ['10.00','20.00','30.00','40.00','50.00','100.00','200.00','1000.00'];
        $status = ['Completed', 'Pending'];
        $yn = ['Y','N'];

        return [
            'branchid' => '1',
            'branchname' => Arr::random($branchname),
            'cabid' => Arr::random([rand(1, 120)]),
            'cabinetname' => rand(0, 120),
            'totalsales' => Arr::random($rpamount),
            'totalcollected' => Arr::random($rpamount),
            'avatarproof' => 'avatars/cash-default.jpg',
            'rnotes' => Str::random(10),
            'userid' => Arr::random([rand(1, 250)]),
            'firstname' => Str::random(1),
            'lastname' => fake()->name(),
            'created_by' => fake()->name(),
            'updated_by' => fake()->name(),
            'posted' => Arr::random($yn),
            'mod' => '0',
            'status' => Arr::random($status),
        ];
    }
}
