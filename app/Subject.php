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
        'strand_id', 'name', 'description', 'hours', 'semester', 
    ];

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
}
