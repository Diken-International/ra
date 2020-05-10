<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::truncate();
        User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'name' => 'Administrator',
            'role' => 'admin'
        ]);
    }
}
