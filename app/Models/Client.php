<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends User
{
    protected $table = 'users';

    public function products(){
        return $this->belongsToMany(Products::class, 'product_user', 'user_id', 'product_id');
    }
}
