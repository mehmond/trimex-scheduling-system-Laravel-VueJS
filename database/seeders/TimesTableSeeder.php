<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Time;
use Illuminate\Support\Facades\Schema;

class TimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Time::truncate();

        Time::create([
            'name' => '07:30-10:30'
        ]);
        Time::create([
            'name' => '10:40-12:30'
        ]);
        Time::create([
            'name' => '12:40-15:30'
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
