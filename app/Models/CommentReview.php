<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReview extends Model
{
    //
    protected $table = 'comment_reviews';

    protected $fillable = [
    	'token_review',
    	'star',
    	'description',
    	'check_revision',
    	'service_id',
    ];

}
