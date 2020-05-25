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
        'costs',
        'extra_cost',
        'total_cost',
        'client_id',
        'technical_id',
        'branch_office_id'
    ];

    protected $hidden = [
        'client_id'
    ];

    public function client(){
        return $this->hasOne(Client::class,'id','client_id');
    }

    public function technical(){
        return $this->hasOne(User::class, 'id', 'technical_id');
    }
}
