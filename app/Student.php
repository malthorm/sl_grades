<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Get the courses the student is enrolled in.
     */
    //TODO: umbenennen in grades()???
    public function enrolledIn()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function addStudent($student)
    {
        $this->students()->create($student);
    }

    // remove all info on student-> was passiert mit den versuchen?
}
