<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradingSheetApproval extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grading_sheet_id', 'user_id', 'status',
    ];

    /**
     * Get the grading sheet that owns the approval.
     */
    public function grading_sheet() {
        return $this->belongsTo('App\GradingSheet');
    }

    /**
     * Get the user that owns the approval.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
}
