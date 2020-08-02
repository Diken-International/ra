<?php


namespace App\Helpers;


use App\Models\Products;

class AvailableHelper
{

    private static $available_type_products = [
        'own'       => "Propio",
        'borrowed'  => "Comodato"
    ];

    public function availableTypeProducts(){
        return array_keys(self::$available_type_products);
    }

    public function availableAllEntities($model){
        return $model::all()->map(function ($entity){
            return $entity->id;
        })->all();
    }

    public function availableByBranchOffice($model,$branch_office_id){
        return $model::where('branch_office_id', $branch_office_id)->get()->map(function ($entity){
            return $entity->id;
        })->all();
    }

}
