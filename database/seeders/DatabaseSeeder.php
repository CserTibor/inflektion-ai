<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrcreate(
            [
                'email' => 'tibor.cser@webensol.com',
            ],
            [
                'name' => 'Tibor Cser',
                'password' => Hash::make('Tibor123!'),
            ]
        );


        $this->call(EmailSeeder::class);
    }
}
