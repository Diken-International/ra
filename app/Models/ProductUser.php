<?php

namespace App\Models;

use App\Traits\FilesModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUser extends Model
{
    use FilesModel;
    use SoftDeletes;

    protected $table = 'product_user';

    protected $fillable = [
      'user_id',
      'product_id',
      'status',
      'product_type',
      'serial_number'
    ];

    protected $appends = [
        'product',
        'client',
        'model',
        'files'
    ];

    public function getProductAttribute(){
        return $this->product()->select('id','name')->first();
    }

    public function getClientAttribute(){
        return $this->client()->select('id','name')->first();
    }

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function client(){
        return $this->hasOne(Client::class, 'id', 'user_id');
    }
}
