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
     * @throws App\Exceptions\InvalidStudentException
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
     * @throws App\Exceptions\InvalidGradingException
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
     * Gets its relative path.
     * @return string
     */
    public function path()
    {
        return "grades/{$this->id}";
    }
}
