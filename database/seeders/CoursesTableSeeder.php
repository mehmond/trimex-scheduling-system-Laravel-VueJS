<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Facades\Schema;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Course::truncate();
        Course::create(['name' => 'BSIT']);
        Course::create(['name' => 'BSTM']);
        Course::create(['name' => 'BSCPE']);
        Course::create(['name' => 'BSBA']);
        Schema::enableForeignKeyConstraints();
    }
}
