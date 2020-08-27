<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    //

    protected $table = 'projects';

    protected $fillable = [
    	'name',
    	'technical_id',
    	'resources'
    ];

    public function getResourcesAttribute($value){
        return json_decode($value);
    }

    public function setResourcesAttribute($value){
        $this->attributes['resources'] =  json_encode($value);
    }

}
