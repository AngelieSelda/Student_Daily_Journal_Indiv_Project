<?php

namespace Database\Seeders;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 fake users
        $users = User::factory(10)->create();

        // Create 50 fake journals and spread them across those 10 users
        Journal::factory(50)
            ->recycle($users)
            ->create();

        // A manager account used to test manager abilities
        User::updateOrCreate(
            ['email' => 'manager@manager.com'],
            [
                'password'   => bcrypt('password'),
                'name'       => 'The Manager',
                'is_manager' => true,
            ]
        );
    }
}