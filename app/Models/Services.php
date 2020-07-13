<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{
    use SoftDeletes;

    protected $table = 'services';

    protected $appends = [
        'model'
    ];

    protected $fillable = [
        'name',
        'type',
        'costs',
        'extra_cost',
        'total_cost',
        'client_id',
        'technical_id',
        'branch_office_id',
        'address',
        'postal_code',
        'state',
        'municipality',
        'contact_phone',
        'progress_status',
        'description',
        'service_start',
        'service_end',
        'product_id'
    ];

    public function getCostsAttribute($value){
        return json_decode($value);
    }

    public function setCostsAttribute($value){
        $this->attributes['costs'] =  json_encode($value);
    }

    public function client(){
        return $this->hasOne(Client::class,'id','client_id');
    }

    public function technical()
    {
        return $this->hasOne(User::class, 'id', 'technical_id');
    }

    public function getModelAttribute(){
        return str_replace('\\', '/', get_class($this));
    }
}
