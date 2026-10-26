<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Journal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // Gumawa ng 10 fake students
        $students = User::factory(10)->create();

        // Gumawa ng 50 journals at ipamahagi sa 10 students na 'yan
        Journal::factory(50)
            ->recycle($students)
            ->create();
    }
}
