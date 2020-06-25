<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectTrack extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'written_work', 'performance_task', 'quarterly_assessment',
    ];

    /**
     * Get the subjects for the subject track.
     */
    public function subjects() {
        return $this->hasMany('App\Subject');
    }
}
