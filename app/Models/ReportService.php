<?php

namespace App\Models;

use App\Traits\FilesModel;
use Illuminate\Database\Eloquent\Model;

class ReportService extends Model
{
    use FilesModel;

    protected $fillable = [
        'service_id',
        'product_user_id',
        'costs',
        'costs_repairs',
        'subtotal',
        'total',
        'progress',
        'description',
        'service_end',
        'service_start',
        'status',
        'dilution',
        'frequency',
        'method'
    ];

    protected $appends = [
        'product_user',
        'files',
        'model'
    ];

    public function getProductUserAttribute($value){
        return $this->productUser()->select([
            'id',
            'product_id',
            'user_id',
            'serial_number'
        ])->first();
    }

    public function productUser(){
        return $this->hasOne(ProductUser::class, 'id', 'product_user_id');
    }

    public function getCostsAttribute($value){
        return json_decode($value);
    }

    public function setCostsAttribute($value){
        $this->attributes['costs'] =  json_encode($value);
    }

    public function getCostsRepairsAttribute($value){
        return json_decode($value);
    }

    public function setCostsRepairsAttribute($value){
        $this->attributes['costs_repairs'] =  json_encode($value);
    }
}
