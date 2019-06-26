<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InvalidGradingException;
use App\Exceptions\InvalidStudentException;

class Grading extends Model
{
    protected $guarded = [];

    /*
     * Decrypts the uni_identifier of the graded student and return it
     * if desired.
     *
     * @param bool $needsReturnValue
     * @retrun mixed (null|string)
     */
    public function decryptUniIdentifier(bool $needsReturnValue = false)
    {
        try {
            $plainUniIdentifier = decrypt($this->student->uni_identifier);
            $this->student->uni_identifier = $plainUniIdentifier;

            if ($needsReturnValue) {
                return $plainUniIdentifier;
            }
        } catch (\Exception $e) {
            throw new InvalidStudentException($e);
        }
    }

    /*
     * Decrypts the grade of the model.
     *
     */
    public function decryptGrade()
    {
        try {
            $plainGrade = decrypt($this->grade);
            $this->grade = $plainGrade;
        } catch (\Exception $e) {
            throw new InvalidGradingException($e);
        }
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
     *
     * @param App\Module $module
     * @param int $studentId
     * @return int
     */
    public function countAttempts($module, $studentId)
    {
        return count($this->attempts($module, $studentId));
    }

    /**
     * Checks if the gradings is the latest attempt in a module.
     *
     * @param App\Module $module
     * @param int $studentId
     * @return bool
     */
    public function islatestAttempt($module, $studentId)
    {
        $attempts = $this->attempts($module, $studentId);
        foreach ($attempts as $attempt) {
            if ($this->id < $attempt->id) {
                return false;
            }
        }
        return true;
    }

    /**
     * Gets all attempts a student has made in a given module.
     *
     * @param App\Module $module
     * @param int $studentId
     * @return Illuminate\Support\Collection
     */
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
