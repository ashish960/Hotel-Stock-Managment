<?php

namespace Database\Seeders;

use \App\Models\User;
use Illuminate\Support\Facades\Hash;

use Faker\Factory as  Faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        $faker=Faker::create();
        $Password = Hash::make('ashish121');
        $roles = ['admin', 'manager'];

        for($i=1;$i<=2;$i++)
        {
            $user = new User;
            $user->email=$faker->email;
            $user->password = $Password;
            $user->role = $roles[array_rand($roles)];
            $user->save();
        }
    }
}
