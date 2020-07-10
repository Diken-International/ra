<?php

use Illuminate\Database\Seeder;

class ProductsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       \Illuminate\Support\Facades\DB::transaction(function (){
           \Illuminate\Support\Facades\DB::table('category')->insert([
               'name' => 'Limpieza y sanitación de áreas y equipos de proceso',
               'description' => 'Limpieza y sanitación de áreas y equipos de proceso'
           ]);

           $sql = base_path("database/files/products.sql");
           \Illuminate\Support\Facades\DB::unprepared(file_get_contents($sql));
       });
    }
}
