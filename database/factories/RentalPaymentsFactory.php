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
        $status = ['Paid', 'Unpaid'];
        $rppaytype = ['Cash','Bank Transfer'];
        $month = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $year = ['2023','2024','2025','2026','2027','2028','2029','2030','2031','2032'];
        $yn = ['Y','N'];
        
        return [
            'userid' => Arr::random([rand(1, 250)]),
            'username' => Str::random(10),
            'firstname' => fake()->name(),
            'lastname' => Str::random(1),
            'rpamount' => Arr::random($rpamount),
            'rppaytype' => Arr::random($rppaytype),
            'rpmonth' => Arr::random($month),
            'rpyear' => Arr::random($year),
            'rpnotes' => Str::random(10),
            'branchid' => '1',
            'branchname' => Arr::random($branchname),
            'cabid' => rand(0, 120),
            'cabinetname' => rand(0, 120),
            'avatarproof' => 'avatars/cash-default.jpg' ,
            'created_by' => fake()->name(),
            'updated_by' => fake()->name(),
            'posted' => Arr::random($yn),
            'status' => Arr::random($status),
        ];
    }
}
