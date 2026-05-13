<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Alice Johnson',   'email' => 'alice@yopmail.com'],
            ['name' => 'Bob Smith',        'email' => 'bob@yopmail.com'],
            ['name' => 'Carol Williams',   'email' => 'carol@yopmail.com'],
            ['name' => 'David Brown',      'email' => 'david@yopmail.com'],
            ['name' => 'Eva Martinez',     'email' => 'eva@yopmail.com'],
            ['name' => 'Frank Wilson',     'email' => 'frank@yopmail.com'],
            ['name' => 'Grace Lee',        'email' => 'grace@yopmail.com'],
            ['name' => 'Henry Taylor',     'email' => 'henry@yopmail.com'],
            ['name' => 'Iris Anderson',    'email' => 'iris@yopmail.com'],
            ['name' => 'Jack Thomas',      'email' => 'jack@yopmail.com'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name'     => $user['name'],
                    'password' => Hash::make('password'),
                ]
            );
        }

        $this->command->info('10 test users seeded successfully!');
    }
}
