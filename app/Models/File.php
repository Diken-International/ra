<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'model',
        'model_id',
        'path',
        'category',
        'type',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(){
        return route('files.show', $this->path);
    }
}
