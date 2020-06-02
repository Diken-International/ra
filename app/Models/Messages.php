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

    	'message',
    	'autor_id',
    	'branch_office_id',
    	'priority',
    	'services_id',
        
    ];
}
