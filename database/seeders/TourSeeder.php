<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Driver;
use App\Models\Guide;
use App\Models\Programme;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //get ids
        $driver_ids = Driver::inRandomOrder()->pluck('id');
        $guide_ids = Guide::inRandomOrder()->pluck('id');
        $programme_ids = Programme::inRandomOrder()->pluck('id');
        $numberOfTours = 20;

        //set a guide, driver, programme for each Tour alternately
        for($i = 0; $i < $numberOfTours; $i++){
            Tour::factory()->create([
                'guide_id' => $guide_ids[$i % $guide_ids->count()],
                'driver_id' => $driver_ids[$i % $driver_ids->count()],
                'programme_id' => $programme_ids[$i % $programme_ids->count()]
            ]);
        }
    }
}
