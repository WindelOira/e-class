<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_track_id', 'strand_id', 'name', 'description', 'hours', 'semester', 
    ];

    /**
     * Get the subject track that owns the subject.
     */
    public function subject_track() {
        return $this->belongsTo('App\SubjectTrack');
    }

    /**
     * Get the strand that owns the subject.
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

    /**
     * Get the subject's subject track.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getSubjectTrackIdAttribute($value) {
        $subjectTrack = SubjectTrack::find($value);

        return $subjectTrack;
    }
}
