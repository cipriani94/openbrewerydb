<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (DB::table('users')->where('username', 'root')->count() === 0)
            DB::table('users')->insert([
                'username' => 'root',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
    }
}
