<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradingSheet extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'level_id', 'section_id', 'subject_id', 'subject_track_id', 'semester', 'quarter',
    ];

    /**
     * Get the section that owns the grading sheet.
     */
    public function section() {
        return $this->belongsTo('App\Section');
    }

    /**
     * Get the grades for the grading sheet.
     */
    public function grades() {
        return $this->hasMany('App\Grade');
    }

    /**
     * Get the students for the grading sheet.
     */
    public function students() {
        return $this->hasMany('App\Student');
    }

    /**
     * Get the comments for the grading sheet.
     */
    public function comments() {
        return $this->hasMany('App\Comment');
    }
    
    /**
     * Get the approvals for the grading sheet.
     */
    public function grading_sheet_approvals() {
        return $this->hasMany('App\GradingSheetApproval');
    }

    /**
     * Get the grading sheet's user.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getUserIdAttribute($value) {
        $user = User::find($value);

        return $user;
    }

    /**
     * Get the grading sheet's section.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getSectionIdAttribute($value) {
        $section = Section::find($value);

        return $section;
    }

    /**
     * Get the grading sheet's subject.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getLevelIdAttribute($value) {
        $level = Level::find($value);

        return $level;
    }

    /**
     * Get the grading sheet's subject.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getSubjectIdAttribute($value) {
        $subject = Subject::find($value);

        return $subject;
    }

    /**
     * Get the grading sheet's subject track.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getSubjectTrackIdAttribute($value) {
        $track = SubjectTrack::find($value);

        return $track;
    }
}
