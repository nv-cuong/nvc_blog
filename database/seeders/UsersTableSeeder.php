<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'role_id' => '1',
                'name' => 'Admin Yesorno',
                'username' => 'admin',
                'email' => 'admin_blog@gmail.com',
                'password' => bcrypt('123456789'),
            ],
            [
                'role_id' => '2',
                'name' => 'Author Yesorno',
                'username' => 'author',
                'email' => 'author_blog@gmail.com',
                'password' => bcrypt('123456789'),
            ]
        ]);
    }
}
