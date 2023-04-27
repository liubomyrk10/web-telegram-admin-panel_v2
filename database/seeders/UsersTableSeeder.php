<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
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
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => null,
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => '2023-03-31 20:52:30',
                'updated_at' => '2023-03-31 20:52:30',
                'permissions' => '{"platform.index": "1", "platform.systems.roles": "1", "platform.systems.users": "1", "platform.systems.attachment": "1"}',
                'telegram_id' => 30000225,
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'username' => 'admin',
                'photo_url' => null
            ],
            [
                'id' => 2,
                'name' => 'ivan',
                'email' => 'ivan@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => '2023-03-31 20:52:30',
                'updated_at' => '2023-03-31 20:52:30',
                'permissions' => '{"platform.index": "1", "platform.systems.roles": "1", "platform.systems.users": "1", "platform.systems.attachment": "1"}',
                'telegram_id' => 6229690325,
                'first_name' => 'Іван',
                'last_name' => 'Мельник',
                'username' => 'test_ivan_uzhnu',
                'photo_url' => 'https://telegra.ph/file/722622534aa056f4caa5e.jpg'
            ]
        ];
        User::query()->insert($users);
    }
}
