<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'type',
        'cost',
        'extra_cost',
        'total_cost',
        'client_id',
        'technical_id'
    ];
}
