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
        $branch_office = BranchOffice::create(['name' => 'Sucursal Celaya']);

        User::create([
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'name' => 'Administrator',
            'role' => 'admin',
            'branch_office_id' => $branch_office->id
        ]);


        User::create([
            'email' => 'administrador@diken.com',
            'password' => 'admin',
            'name' => 'Sergio',
            'role' => 'admin',
            'branch_office_id' => $branch_office->id
        ]);

        User::create([
            'email' => 'ccs.celaya@dikeninternational.com',
            'password' => 'tecnico',
            'name' => 'tecnico',
            'role' => 'tecnico',
            'branch_office_id' => $branch_office->id
        ]);

        /*
        $faker = \Faker\Factory::create();

        for ($i=0; $i<= 5; $i++){
            User::create([
                'email' => $faker->email,
                'password' => 'test',
                'name' => $faker->name,
                'last_name' => $faker->lastName,
                'role' => \Illuminate\Support\Arr::random(['admin', 'tecnico', 'asesor', 'cliente']),
                'branch_office_id' => $branch_office->id
            ]);
        }
        */

    }
}
