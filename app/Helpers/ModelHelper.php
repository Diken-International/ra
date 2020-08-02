<?php

namespace App\Helpers;
use App\Exceptions\Api\EntityNotFoundException;

class ModelHelper
{
    /**
     * @param $model
     * @param $model_id
     * @param array $filters
     * @return mixed
     * @throws EntityNotFoundException
     */
    public static function findEntity($model, $model_id, $filters = []){

        $entity = $model::where('id',$model_id)->where($filters)->first();

        if (!$entity instanceof $model){
            throw new EntityNotFoundException($model->name() . 'Model not found');
        }
        return $entity;
    }
}
