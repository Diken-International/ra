<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{
    use SoftDeletes;

    protected $table = 'services';

    protected $appends = [
        'model',
        'reports',
        'comments'
    ];

    protected $fillable = [
        'client_id',
        'technical_id',
        'branch_office_id',
        'tentative_date',
        'type',
        'kms',
        'activity'
    ];

    public function getReportsAttribute($value){
        return $this->reportServices()->get();
    }

    public function getCommentsAttribute($value){
        return $this->comments()->get();
    }

    public function getCostsAttribute($value){
        return json_decode($value);
    }

    public function setCostsAttribute($value){
        $this->attributes['costs'] =  json_encode($value);
    }

    public function getRepairsAttribute($value){
        return json_decode($value);
    }

    public function setRepairsAttribute($value){
        $this->attributes['repairs'] =  json_encode($value);
    }

    public function reportServices(){
        return $this->hasMany(ReportService::class, 'service_id', 'id');
    }

    public function comments(){
        return $this->hasMany(CommentReview::class, 'service_id', 'id');
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
