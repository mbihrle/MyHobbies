<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User(
            [
                'name' => 'Marco_Admin',
                'email' => 'bihrle.marco@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('marco'),
                'remember_token' => Str::random(10),
                'rolle' => 'admin'
            ]
        );

        $admin->save();
    }
}
