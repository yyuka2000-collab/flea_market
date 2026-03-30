<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'              => 'テストユーザー1',
                'email'             => 'test1@example.com',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'テストユーザー2',
                'email'             => 'test2@example.com',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'テストユーザー3',
                'email'             => 'test3@example.com',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
