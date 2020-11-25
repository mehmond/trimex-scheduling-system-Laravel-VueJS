<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Schema;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();

        Role::create([
            'name' => 'Admin',
            'permission' => '[{"resourceName":"Home","read":true,"write":true,"update":true,"delete":true,"name":""},{"resourceName":"Course","read":true,"write":true,"update":true,"delete":true,"name":"course"},{"resourceName":"Subject","read":true,"write":true,"update":true,"delete":true,"name":"subject"},{"resourceName":"Section","read":true,"write":true,"update":true,"delete":true,"name":"section"},{"resourceName":"Campus","read":true,"write":true,"update":true,"delete":true,"name":"campus"},{"resourceName":"Room","read":true,"write":true,"update":true,"delete":true,"name":"room"},{"resourceName":"Time","read":true,"write":true,"update":true,"delete":true,"name":"time"},{"resourceName":"Users","read":true,"write":true,"update":true,"delete":true,"name":"users"},{"resourceName":"Assign Role","read":true,"write":true,"update":true,"delete":true,"name":"assign-role"}]'
        ]);
        Role::create([
            'name' => 'Faculty',
            'permission' => '[{"resourceName":"Home","read":true,"write":true,"update":true,"delete":true,"name":""},{"resourceName":"Course","read":true,"write":false,"update":false,"delete":false,"name":"course"},{"resourceName":"Subject","read":true,"write":false,"update":false,"delete":false,"name":"subject"},{"resourceName":"Section","read":true,"write":false,"update":false,"delete":false,"name":"section"},{"resourceName":"Campus","read":false,"write":false,"update":false,"delete":false,"name":"campus"},{"resourceName":"Room","read":false,"write":false,"update":false,"delete":false,"name":"room"},{"resourceName":"Time","read":false,"write":false,"update":false,"delete":false,"name":"time"},{"resourceName":"Users","read":false,"write":false,"update":false,"delete":false,"name":"users"},{"resourceName":"Assign Role","read":false,"write":false,"update":false,"delete":false,"name":"assign-role"},{"resourceName":"Preferred Subject","read":true,"write":true,"update":true,"delete":true,"name":"preferred-subject"},{"resourceName":"Preferred Time","read":true,"write":true,"update":true,"delete":true,"name":"preferred-time"}]'
        ]);
        Role::create([
            'name' => 'User',
            'permission' => '[{"resourceName":"Home","read":true,"write":true,"update":true,"delete":true,"name":"/"},{"resourceName":"Course","read":false,"write":false,"update":false,"delete":false,"name":"course"},{"resourceName":"Users","read":false,"write":false,"update":false,"delete":false,"name":"users"},{"resourceName":"Assign Role","read":false,"write":false,"update":false,"delete":false,"name":"assign-role"}]'
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
