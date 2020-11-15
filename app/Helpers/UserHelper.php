<?php


namespace App\Helpers;


use App\Models\User;
use Illuminate\Support\Str;

class UserHelper
{

    public static function checkEmail(User $user){
        return (Str::endsWith($user->email, 'cliente.com')) ? false : true;
    }
}
