<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class RepairsParts extends Model
{
    //use SoftDeletes;

    protected $table = 'repair_parts';

    protected $fillable = [
    	'code',
    	'parts_num',
    	'number_diken',
    	'category_repair_parts_id',
    	'product_repair_parts_id',
    	'name',
    	'features',
    	'quantity',
    	'number_of_part',
    ];
}
