<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $guarded = [];


    /**
     * Get the student enrolled in the course.
     */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    /**
     * Get the course in which the student is enrolled.
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }
}
