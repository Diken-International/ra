<?php


namespace App\Helpers;


class AvailableHelper
{

    public function availableByBranchOffice($model,$branch_office_id){
        return $model::where('branch_office_id', $branch_office_id)->get()->map(function ($entity){
            return $entity->id;
        })->all();
    }
}
