<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grading_sheet_id', 'user_id', 'content',
    ];

    /**
     * Get the grading sheet that owns the comment.
     */
    public function grading_sheet() {
        return $this->belongsTo('App\GradingSheet');
    }

    /**
     * Get the user that owns the comment.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the comment's user.
     * 
     * @param   string  $value
     * @return  object
     */
    public function getUserIdAttribute($value) {
        $user = User::find($value);

        return $user;
    }
}
