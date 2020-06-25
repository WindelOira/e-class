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
        'code', 'description',
    ];

    /**
     * Get sections that belong to the strand.
     */
    public function sections() {
        return $this->belongsToMany('App\Section');
    }
}
