<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the strands that are assigned to this track.
     */
    public function strands() {
        return $this->hasMany('App\Strand');
    }

    /**
     * Get all of the classes that are assigned to this track.
     */
    public function classes() {
        return $this->morphedByMany('App\Classes', 'trackable');
    }
}
