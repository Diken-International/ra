<?php

use Illuminate\Database\Seeder;

class RepairSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $products_repair = [
        'Despiece Serie D’Cart',        // 1
        'Despiece Serie D’Cart Duplex', // 2
        'Despiece Steel Foam',          // 3
        'Despiece Wall Foam',           // 4
        'Despiece Mini Wall Foam',      // 5
        'Door Way Sanitizer',           // 6
        'Mini Wall Sanitizer',          // 7
        'Nebulizador Six Foger',        // 8
        'Central System',               // 9
        'Lyster Quat 7” (redonda)',     // 10
        'Lyster Quat 9” (redonda)',     // 11
        'Lyster Quat (25 cm cuadrados)',// 12
        'Chemical Transfer',            // 13
        'Cleaning Control System',      // 14
    ];

    private $categories = [
        'Equipos espumados móviles',    // 1
        'Equipo Espumador Presurizado', // 2
        'Equipos Espumadores de Pared', // 3
        'Equipos Sanitizadores',        // 4
        'Equipos Nebulizador',          // 5
        'Central System',               // 6
        'Equipo de Drenajes',           // 7
        'Trasvase Neumático',           // 8
        'CSS'                           // 9
    ];

    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function (){
            foreach ($this->products_repair as $repair){
                \Illuminate\Support\Facades\DB::table('products_repair_parts')->insert([
                    'name' => $repair
                ]);
            }

            foreach ($this->categories as $category){
                \Illuminate\Support\Facades\DB::table('category_repair_parts')->insert([
                    'name' => $category
                ]);
            }

            $sql = base_path("database/files/repairs.sql");
            \Illuminate\Support\Facades\DB::unprepared(file_get_contents($sql));
        });
    }
}
