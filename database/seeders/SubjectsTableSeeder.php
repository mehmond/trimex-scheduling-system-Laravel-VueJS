<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use Illuminate\Support\Facades\Schema;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Subject::truncate();
        Subject::create([
            'name' => 'IT 214',
            'description' => 'Web Development',
            'lec' => '2',
            'lab' => '1',
            'type' => 'Computer Laboratory',
            'course_id' => 1,
            'year_id' => 3
        ]);
        Subject::create([
            'name' => 'IT 204',
            'description' => 'System Analysis and Design',
            'lec' => '2',
            'lab' => '1',
            'type' => 'Computer Laboratory',
            'course_id' => 1,
            'year_id' => 3
        ]);
        Subject::create([
            'name' => 'IT 213',
            'description' => 'Database Management System 2',
            'lec' => '2',
            'lab' => '1',
            'type' => 'Computer Laboratory',
            'course_id' => 1,
            'year_id' => 3
        ]);
        Subject::create([
            'name' => 'IT 301',
            'description' => 'IT Elective 1 (JAVA)',
            'lec' => '2',
            'lab' => '1',
            'type' => 'Computer Laboratory',
            'course_id' => 1,
            'year_id' => 3
        ]);
        Subject::create([
            'name' => 'SOCSCI 3',
            'description' => 'Society & Culture with Family Planning',
            'lec' => '3',
            'lab' => '0',
            'type' => 'Lecture',
            'course_id' => 1,
            'year_id' => 3
        ]);
        Subject::create([
            'name' => 'FIL 3',
            'description' => 'Masining na Pagpapahayag',
            'lec' => '3',
            'lab' => '0',
            'type' => 'Lecture',
            'course_id' => 1,
            'year_id' => 3
        ]);
        Subject::create([
            'name' => 'HUM 3',
            'description' => 'Logic',
            'lec' => '3',
            'lab' => '0',
            'type' => 'Lecture',
            'course_id' => 1,
            'year_id' => 3
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
