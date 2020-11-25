<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campus;
use Illuminate\Support\Facades\Schema;

class CampusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Campus::truncate();
        
        Campus::insert([
            ['name' => 'Main'],
            ['name' => 'Annex'],
            ['name' => 'Santa Catalina'],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
