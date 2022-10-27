<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        //
        User::create([
            'name' => 'Super Admin',
            'role_id'  => 1,
            'email' => 'admin@mail.com',
            'password' => bcrypt('12345678')
        ]);
    }
  

}
