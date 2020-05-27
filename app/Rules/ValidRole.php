<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ValidRole implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $users_id = User::where(['role' => $this->role])->get()->map(function ($user){
            return $user->id;
        });

        return in_array($value,$users_id->all());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The role '.$this->role.' not match with a correct role';
    }
}
