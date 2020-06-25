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
        'subject_track_id', 'name', 'description', 'hours',
    ];

    /**
     * Get the subject track that owns the subject.
     */
    public function subject_track() {
        return $this->belongsTo('App\SubjectTrack');
    }

    /**
     * Get the subject's subject track.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getSubjectTrackIdAttribute($value) {
        $track = SubjectTrack::find($value);

        return $track;
    }
}
