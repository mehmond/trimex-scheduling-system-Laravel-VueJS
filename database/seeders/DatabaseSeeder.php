<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            CampusesTableSeeder::class,
            TimesTableSeeder::class,
            CoursesTableSeeder::class,
            YearsTableSeeder::class,
            RoomsTableSeeder::class,
            DaysTableSeeder::class,
            UsersTableSeeder::class,
            SubjectsTableSeeder::class
            ]);
    }
}
