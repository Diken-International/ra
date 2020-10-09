<?php

namespace App\Models;

use App\Traits\FilesModel;
use Illuminate\Database\Eloquent\Model;

class ProductUser extends Model
{
    use FilesModel;

    protected $table = 'product_user';

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'product_type',
        'serial_number',
        'period_service',
        'next_service',
        'last_service',
        'update'
    ];

    protected $appends = [
        'product',
        'client',
        'number_of_services_performed',
        'model',
        'files'
    ];

    public function getProductAttribute(){
        return $this->product()->select('id','name')->first();
    }

    public function getClientAttribute(){
        return $this->client()->select('id','name', 'business_name', 'rfc', 'company_name')->first();
    }

    public function getNumberofservicesperformedAttribute(){
        return $this->reportservices()->where('product_user_id', $this->id)->sum('product_user_id');
    }

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function client(){
        return $this->hasOne(Client::class, 'id', 'user_id');
    }

    public function reportservices(){
        return $this->hasMany(ReportService::class, 'product_user_id', 'id');
    }
}
