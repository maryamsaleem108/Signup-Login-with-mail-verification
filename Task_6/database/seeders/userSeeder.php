<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserToken;
use Faker\Factory as faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Dummy Data (10 Rows)
        for ($i=1;$i<=10;$i++){
            $faker = faker::create();
            $user = new User();
            $user->name = $faker->name;
            $user->age = 20;
            $user->email = Str::random(4).'@gmail.com';
            $user->phone_number = 03000000000;
            $user->password = Str::random(9);
            $user->picturePath = Str::random();
            $user->save();
        }
        for ($i=1;$i<=10;$i++){
            $token = new UserToken();
            $token->email = Str::random(4).'@gmail.com';
            $token->userToken = Str::random(10);
            $token->save();
        }
    }
}
