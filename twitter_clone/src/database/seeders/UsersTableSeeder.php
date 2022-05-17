<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name'           => 'TEST' .$i,
                'profile_image'  => 'https://placehold.jp/50x50.png',
                'email'          => 'test' .$i .'@test.com',
                'password'       => 'jfiojfihifdh3jkhh3h',
                'remember_token' => str_random(10),
                'created_at'     => now(),
                'updated_at'     => now()
            ]);
        }
    }
}
