<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id', 'name',
    ];

    /**
     * Get all of the metas for the student.
     */
    public function metas() {
        return $this->morphToMany('App\Student', 'metaable');
    }

    /**
     * Get attendances that belongs to student.
     */
    public function attendances() {
        return $this->belongsToMany('App\Attendance');
    }

    /**
     * Get section that belongs to student.
     */
    public function section() {
        return $this->belongsTo('App\Section');
    }
}
