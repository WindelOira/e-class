<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grading_sheet_id', 'student_id', 'written_work', 'performance_task', 'quarterly_assessment',
    ];

    /**
     * Get the grading sheet that owns the grade.
     */
    public function grading_sheet() {
        return $this->belongsTo('App\GradingSheet');
    }

    /**
     * get the student that owns the grade.
     */
    public function student() {
        return $this->belongsTo('App\Student');
    }

    /**
     * Get the grade's student.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getStudentIdAttribute($value) {
        $student = Student::find($value);

        return $student;
    }

    /**
     * Get the grade's written work.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getWrittenWorkAttribute($value) {
        return json_decode($value);
    }

    /**
     * Get the grade's performance task.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getPerformanceTaskAttribute($value) {
        return json_decode($value);
    }

    /**
     * Get the grade's quarterly assessment.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getQuarterlyAssessmentAttribute($value) {
        return json_decode($value);
    }
}
