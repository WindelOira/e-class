<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_time',
    ];

    /**
     * Get the students for the attendace.
     */
    public function students() {
        return $this->belongsToMany('App\Student');
    }
}
