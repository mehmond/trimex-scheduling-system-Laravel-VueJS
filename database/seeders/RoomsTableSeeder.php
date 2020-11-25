<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Campus;
use Illuminate\Support\Facades\Schema;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Room::truncate();
      Schema::disableForeignKeyConstraints();
      $mainID = Campus::all()->where('name', 'Main')->pluck('id')->first();
      $annexID = Campus::all()->where('name', 'Annex')->pluck('id')->first();
      $scID = Campus::all()->where('name', 'Santa Catalina')->pluck('id')->first();
      Room::create([
        'name' => 'M101',
        'campus_id' => $mainID,
        'type' => 'Computer Lab'
      ]);

      Room::create([
        'name' => 'A101',
        'campus_id' => $annexID,
        'type' => 'Lecture Room'
      ]);

      Room::create([
        'name' => 'SC101',
        'campus_id' => $scID,
        'type' => 'Elec Lab'
      ]);
      Schema::enableForeignKeyConstraints();
    }
}
