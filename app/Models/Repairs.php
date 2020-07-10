<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repairs extends Model
{
    //
    use SoftDeletes;

    protected $table = 'repairs';

    protected $fillable = [
    	'code',
    	'name',
    	'category',
    	'quantity',
    	'cost',
    	'price',
    ];
    
}
