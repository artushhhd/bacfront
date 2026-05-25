<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'aaa@mail.ru'],
            [
                'name' => 'aaa',
                'password' => Hash::make('aaa'),
                'role' => 'superadmin',
            ]
        );

        // 2. Moderator (bbb)
        User::updateOrCreate(
            ['email' => 'bbb@mail.ru'],
            [
                'name' => 'bbb',
                'password' => Hash::make('bbb'),
                'role' => 'moderator',
            ]
        );
        User::updateOrCreate(
            ['email' => 'ccc@mail.ru'],
            [
                'name' => 'ccc',
                'password' => Hash::make('ccc'),
                'role' => 'admin',
            ]
        );
        $users = [
            ['name' => 'ddd', 'email' => 'ddd@mail.ru', 'password' => 'ddd'],
            ['name' => 'vvv', 'email' => 'vvv@mail.ru', 'password' => 'vvv'],
            ['name' => 'eee', 'email' => 'eee@mail.ru', 'password' => 'eee'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => 'user',
                ]
            );
        }
    }
}
