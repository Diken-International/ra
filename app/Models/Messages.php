<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Messages extends Model
{
    //
    use SoftDeletes;
    
    protected $table = 'messages';

    protected $fillable = [
    	'created_date',
    	'message',
    	'autor_id',
    	'priority',
    	'services_id',
    ];
}
