<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class YearsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('years')->truncate();

        DB::table('years')->insert([
            ['name' => '1st'],
            ['name' => '2nd'],
            ['name' => '3rd'],
            ['name' => '4th'],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
