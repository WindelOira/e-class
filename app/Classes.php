<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'academic_year_id', 'subject_id', 'level_id', 'section_id', 'strand_id', 'hours', 'year', 'semester',
    ];

    /**
     * Get the academic year that owns the class.
     */
    public function academic_year() {
        return $this->belongsTo('App\AcademicYear');
    }

    /**
     * Get the subject that owns the class.
     */
    public function subject() {
        return $this->belongsTo('App\Subject');
    }

    /**
     * Get the level that owns the class.
     */
    public function level() {
        return $this->belongsTo('App\Level');
    }

    /**
     * Get the section that owns the class.
     */
    public function section() {
        return $this->belongsTo('App\Section');
    }

    /**
     * Get the strand that owns the class.
     */
    public function strand() {
        return $this->belongsTo('App\Strand');
    }

    /**
     * Get the sections academic year.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getAcademicYearIdAttribute($value) {
        $year = AcademicYear::find($value);

        return $year;
    }

    /**
     * Get the sections subject.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getSubjectIdAttribute($value) {
        $subject = Subject::find($value);

        return $subject;
    }

    /**
     * Get the sections level.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getLevelIdAttribute($value) {
        $level = Level::find($value);

        return $level;
    }

    /**
     * Get the sections section.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getSectionIdAttribute($value) {
        $section = Section::find($value);

        return $section;
    }

    /**
     * Get the sections strand.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getStrandIdAttribute($value) {
        $strand = Strand::find($value);

        return $strand;
    }
}
