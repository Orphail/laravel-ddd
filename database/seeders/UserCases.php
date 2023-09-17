<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserCases extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'username' => 'manager',
            'email' => 'manager@manager.com',
            'name' => 'manager',
            'password' => Hash::make('manager'),
            'last_login' => '2020-09-01 16:16:16',
            'is_active' => true,
            'role' => 'manager',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'username' => 'agent',
            'email' => 'agent@agent.com',
            'name' => 'agent',
            'password' => Hash::make('agent'),
            'last_login' => '2020-09-01 16:16:16',
            'is_active' => true,
            'role' => 'agent',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
