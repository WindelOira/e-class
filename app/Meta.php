<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value',
    ];

    /**
     * Get all of the users that are assigned this meta.
     */
    public function users() {
        return $this->morphedByMany('App\User', 'metaable');
    }

    /**
     * Get all of the students that are assigned this meta.
     */
    public function students() {
        return $this->morphedByMany('App\Student', 'metaable');
    }
}
