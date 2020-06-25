<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'role', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * get all of the metas for the user.
     */
    public function metas() {
        return $this->morphToMany('App\Meta', 'metaable');
    }

    /**
     * The subjects that belong to the user.
     */
    public function subjects() {
        return $this->belongsToMany('App\Subject');
    }

    /**
     * The students that belong to the user.
     */
    public function students() {
        return $this->belongsToMany('App\Student');
    }

    /**
     * Get the comments for the user.
     */
    public function comments() {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get the approvals for the user.
     */
    public function grading_sheet_approvals() {
        return $this->hasMany('App\GradingSheetApproval');
    }
}
