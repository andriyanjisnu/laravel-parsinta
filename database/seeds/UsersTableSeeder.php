<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Andri Yan Jisnu',
            'username' => 'andriyanjisnu',
            'password' => bcrypt('password'),
            'email' => 'andriyanj@yahoo.com',
        ]);
    }
}
