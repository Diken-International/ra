<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReview extends Model
{
    //
    protected $table = 'comment_reviews';

    protected $fillable = [
    	'star',
    	'description',
    	'check_revision',
    	'service_id',
    ];

    public function service(){
        return $this->belongsTo(Services::class)->select(['id', 'client_id', 'technical_id'])->with(['client', 'technical']);
    }

}
