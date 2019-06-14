<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grading extends Model
{
    // use \App\Traits\Encryptable;

    protected $guarded = [];
    // protected $encryptable = [
    //     'grade'
    // ];

    public function decryptUniIdentifier(bool $needsReturnValue = false)
    {
        $plainUniIdentifier = decrypt($this->student->uni_identifier);
        $this->student->uni_identifier = $plainUniIdentifier;

        if ($needsReturnValue) {
            return $plainUniIdentifier;
        }
    }

    // do grades need to be encrypted?
    public function decryptGrade()
    {
        $plainGrade = decrypt($this->grade);
        $this->grade = $plainGrade;
    }

    /**
     * Get the student to whom this grading belongs.
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
            // higher enrollment id means created later(maybe should check semester instead) //use created_at?
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
        $attempts = Grading::whereIn('course_id', $courseIds)
            ->where('student_id', $studentId)
            ->get()
        ;
        return $attempts;
    }
}
