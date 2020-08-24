<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'second_last_name',
        'email',
        'password',
        'role',
        'branch_office_id',
        'business_name',
        'rfc',
        'company_name',
        'contacts'
    ];

    /**
     * The SoftDeletes in the table.
     *
     * @var array
     */

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at'
    ];

    protected $appends = [
        'services'
    ];
    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(){
        return $this->hasMany(Messages::class);
    }

    public function getActivitiesAttribute($value){
        return json_decode($value);
    }

    public function setActivitiesAttribute($value){
        $this->attributes['activities'] =  json_encode($value);
    }

    public function getServicesAttribute(){

        $query_week = DB::table('services')->where('technical_id', $this->id)->whereBetween('tentative_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()]
        )->orderBy('tentative_date', 'asc');
        $query_month = DB::table('services')->where('technical_id', $this->id)->whereBetween('tentative_date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()]
        )->orderBy('tentative_date', 'asc');

        return [
            'week' => $query_week->get(),
            'month' => $query_month->get(),
        ];
    }

}
