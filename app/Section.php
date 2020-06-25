<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'academic_year_id', 'strand_id', 'level_id', 'user_id', 'name',
    ];

    /**
     * Get the strand that owns the class.
     */
    public function strand() {
        return $this->belongsTo('App\Strand');
    }

    /**
     * Get the level that owns the class.
     */
    public function level() {
        return $this->belongsTo('App\Level');
    }

    /**
     * Get the user that owns the class.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the students for the section.
     */
    public function students() {
        return $this->hasMany('App\Student');
    }

    /**
     * Get the grading sheets for the section.
     */
    public function grading_sheets() {
        return $this->hasMany('App\GradingSheet');
    }

    /**
     * Get the section's academic year.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getAcademicYearIdAttribute($value) {
        $year = AcademicYear::find($value);

        return $year;
    }

    /**
     * Get the section's user.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getUserIdAttribute($value) {
        $user = User::find($value);

        return $user;
    }

    /**
     * Get the section's strand.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getStrandIdAttribute($value) {
        $strand = Strand::find($value);

        return $strand;
    }

    /**
     * Get the section's level.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getLevelIdAttribute($value) {
        $level = Level::find($value);

        return $level;
    }

    /**
     * Get the section's grading sheet.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getGradingSheetsAttribute($value) {
        $gradingSheets = [];
        foreach( $this->grading_sheets()->get() as $sheet ) :
            $gradingSheet = $sheet;
            $gradingSheet['grades'] = $sheet->grades;

            $gradingSheets[] = $gradingSheet;
        endforeach;

        return $gradingSheets;
    }
}
