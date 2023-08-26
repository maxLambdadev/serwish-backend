<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'dev',
            'email'=>'poberezhetsdev@gmail.com',
            'phone_number'=>'111111111',
            'password'=>bcrypt('aaaaaaaaa'),
        ]);

    }
}
