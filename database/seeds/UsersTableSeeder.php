<?php

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
        DB::table('user')->insert([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => app('hash')->make('admin'),
            'group' => 'admin',
        ]);
    }
}
