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
        'reports'
    ];

    protected $fillable = [
        'client_id',
        'technical_id',
        'branch_office_id'
    ];

    public function getReportsAttribute($value){
        return $this->reportServices()->get();
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
