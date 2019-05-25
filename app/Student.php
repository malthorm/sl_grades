<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'student_id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Get the courses the student is enrolled in.
     */
    public function enrolledIn()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function addStudent($student)
    {
        $this->students()->create($student);
    }
}
