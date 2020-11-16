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

}
