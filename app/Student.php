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
        'section_id', 'strand_id', 'student_number', 'name',
    ];

    /**
     * Get all of the metas for the student.
     */
    public function metas() {
        return $this->morphToMany('App\Meta', 'metaable');
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

    /**
     * Get strand that belongs to student.
     */
    public function strand() {
        return $this->belongsTo('App\Strand');
    }

    /**
     * Get the subject's strand.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getStrandIdAttribute($value) {
        $strand = Strand::find($value);

        return $strand;
    }
}
