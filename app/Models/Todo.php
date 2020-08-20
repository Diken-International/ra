<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'description',
        'type',
        'activity',
        'date',
        'kms',
        'technical_id',
        'client_id',
        'status'
    ];
}
