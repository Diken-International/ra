<?php

use App\Models\BranchOffice;
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
        $branch_office = BranchOffice::create(['name' => 'Sucursal Test']);

        User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'name' => 'Administrator',
            'role' => 'admin',
            'branch_office_id' => $branch_office->id
        ]);
    }
}
