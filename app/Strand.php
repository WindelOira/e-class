<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Strand extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'track_id', 'code', 'description',
    ];

    /**
     * Get the track that owns the subject.
     */
    public function track() {
        return $this->belongsTo('App\Track');
    }

    /**
     * Get sections that belong to the strand.
     */
    public function sections() {
        return $this->belongsToMany('App\Section');
    }

    /**
     * Get the stramd's track.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getTrackIdAttribute($value) {
        $track = Track::find($value);

        return $track;
    }
}
