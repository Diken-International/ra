<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'name',
        'type',
        'costs',
        'extra_cost',
        'total_cost',
        'client_id',
        'technical_id'
    ];
}
