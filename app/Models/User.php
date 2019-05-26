<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable // implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];
*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function isAdmin() : bool {
        return ($this->role == 'admin');
    }

    /**
     * The relation with userexams (OneToMany)
     */
    public function userexams() {
        return $this->hasMany('App\Models\UserExam','user_id', 'id');
    }

    /**
     * The exams a user has started (and has yet to finish)
     */
    public function startedExams() {
        return $this->userexams()->where('result', null);
    }

}
