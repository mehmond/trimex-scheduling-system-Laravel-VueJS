<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        $adminRole = Role::all()->where('name', 'Admin')->pluck('id')->first();
        $facultyRole = Role::all()->where('name', 'Faculty')->pluck('id')->first();
        $userRole = Role::all()->where('name', 'User')->pluck('id')->first();
        $admin = User::create([
            'full_name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role_id' =>  $adminRole
        ]);

        $faculty = User::create([
            'full_name' => 'Faculty User',
            'email' => 'faculty@faculty.com',
            'password' => Hash::make('password'),
            'role_id' =>  $facultyRole
        ]);

        $user = User::create([
            'full_name' => 'Generic User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'role_id' =>  $userRole
        ]);
        return ([
                $admin,
                $faculty,
                $user
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
