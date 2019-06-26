<?php

namespace App;

use App\Exceptions\Handler;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InvalidGradingException;

class Course extends Model
{
    protected $guarded = [];

    /**
     * Get the gradings of the course.
     */
    public function gradings()
    {
        return $this->hasMany(Grading::class);
    }

    /**
     * Get the module on which the course is based.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }


    /*
     * Checks if a grading for the course exists already for the given
     * identifier.
     *
     * @param string $uniIdentifier
     * @return bool
     */
    public function isGraded(string $uni_identifier)
    {
        // a student can't be graded twice
        foreach ($this->gradings as $grading) {
            $match = $grading->student->getPlainUniIdentifier();
            if ($match == $uni_identifier) {
                return true;
            }
        }
        return false;
    }

    /*
     * Creates a new Grading for the student.
     *
     * @param \App\Student $student
     * @param string $grade
     * @return \App\Grading
     */
    public function gradeStudent(Student $student, string $grade)
    {
        $student_id = $student->id;
        $grade = encrypt($grade);
        return $this->gradings()->create(compact(
            'student_id',
            'grade'
        ));
    }

    /**
     * Update the course with the given attributes if there isn't a course with
     * the new attributes already in storage.
     *
     * @param App\Module $module
     * @param string $semester
     * @return App\Course
     */
    public function updateAttributes(Module $module, string $semester)
    {
        if ($this->duplicate($module->id, $semester)) {
            throw new \Exception('Course must not be duplicate');
        }
        $this->module_id = $module->id;
        $this->semester = $semester;
        $this->load('module')->save();
        return $this;
    }

    /**
     * Check if a the specified course already exists in storage.
     *
     * @param App\Module $module
     * @param string $semester
     * @return mixed (bool || App\Course)
     */
    public function duplicate(int $moduleId, string $semester)
    {
        return Course::where('module_id', $moduleId)
                ->where('semester', $semester)
                ->first();
    }
}
