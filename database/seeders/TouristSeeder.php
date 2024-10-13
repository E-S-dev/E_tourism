<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tourist;
use App\Models\Tour;
use App\Models\User;
class TouristSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //count Tour records
        $tour = Tour::count();
        //get Tour ids in random
        $tour_ids = Tour::inRandomOrder()->take($tour)->pluck('id');

        //generate 10 tourists in each tour
        foreach($tour_ids as $tour_id){
            Tourist::factory()->count(10)->has(User::factory())->create(['tour_id' => $tour_id]);
        }

    }
}
