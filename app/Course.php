<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    /**
     * Get the enrollments of the course.
     */
    public function enrolled()
    {
        return $this->hasMany('App\Enrollment');
    }

    /**
     * Get the module of the semester course.
     */
    public function module()
    {
        return $this->belongsTo('App\Module');
    }

    public function addCourse($course)
    {
        $this->courses()->create($course);
    }
}
