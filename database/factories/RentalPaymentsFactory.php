<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RentalPaymentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $rpamount = ['10.00','20.00','30.00','40.00','50.00','100.00','200.00','1000.00'];
        $branchname = ['CB Main', 'CB Annex', 'CB Complex', 'CB Plus 1', 'CB Plus 2', 'CB Plus 3'];
        $status = ['Active', 'Inactive'];
        $rppaytype = ['Cash','Bank Transfer'];
        $monthyear = ['January 2023','February 2023','March 2023','April 2023','May 2023','June 2023','July 2023','August 2023','September 2023','October 2023','November 2023','December 2023'];
        
        return [
            'userid' => Arr::random([rand(1, 250)]),
            'username' => Str::random(10),
            'firstname' => fake()->name(),
            'lastname' => Str::random(1),
            'rpamount' => Arr::random($rpamount),
            'rppaytype' => Arr::random($rppaytype),
            'rpmonthyear' => Arr::random($monthyear),
            'rpnotes' => Str::random(10),
            'branchid' => '1',
            'branchname' => Arr::random($branchname),
            'cabid' => rand(0, 120),
            'cabinetname' => rand(0, 120),
            'avatarproof' => 'avatars/cash-default.png' ,
            'created_by' => fake()->name(),
            'status' => Arr::random($status),
        ];
    }
}
