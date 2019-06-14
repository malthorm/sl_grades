<?php

//TODO: in Grades/Gradings umbennenen? auch methoden etc
namespace App;

use \App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $guarded = [];
    protected $encryptable = [
        'student_id',
        'course_id',
        'grade'
    ];


    /**
     * Get the student enrolled in the course.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the course in which the student is enrolled.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Count the number of times a student was graded
     * in a course based on the same module.
     */
    public function countAttempts($module, $studentId)
    {
        return count($this->attempts($module, $studentId));
    }

    public function islatestAttempt($module, $studentId)
    {
        $attempts = $this->attempts($module, $studentId);
        foreach ($attempts as $attempt) {
            // higher enrollment id means created later(maybe should check semester instead)
            if ($this->id < $attempt->id) {
                return false;
            }
        }
        return true;
    }

    private function attempts($module, $studentId)
    {
        $courseIds = array();
        foreach ($module->courses as $course) {
            array_push($courseIds, $course->id);
        }
        $attempts = Enrollment::whereIn('course_id', $courseIds)
            ->where('student_id', $studentId)
            ->get()
        ;
        return $attempts;
    }
}
