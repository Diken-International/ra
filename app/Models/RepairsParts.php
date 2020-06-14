<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepairsParts extends Model
{
    //
    use SoftDeletes;

    protected $table = 'repair_parts';

    protected $fillable = [
    	'product_id',
    	'spare_part_id',
    	'number_of_part',
    ];
}
