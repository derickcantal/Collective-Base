<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class attendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['Active', 'Inactive'];
        $branchname = ['CB Main', 'CB Annex', 'CB Complex', 'CB Plus 1', 'CB Plus 2', 'CB Plus 3'];

        return [
            'userid' => Arr::random([rand(1, 250)]),
            'username' => Str::random(10),
            'firstname' => fake()->name(),
            'lastname' => Str::random(1),
            'branchid' => '1',
            'branchname' => Arr::random($branchname),
            'attnotes' => Str::random(10),
            'created_by' => fake()->name(),
            'updated_by' => fake()->name(),
            'status' => Arr::random($status),
        ];
    }
}
