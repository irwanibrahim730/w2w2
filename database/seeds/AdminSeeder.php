<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_fname' => 'admin',
            'user_email' => 'admin@admin.com',
            'password' => 'admin',
            'user_role' => 'admin',
        ]);
            
    }
}
