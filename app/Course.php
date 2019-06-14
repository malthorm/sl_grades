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
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the module of the semester course.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    //TODO: better verb than enroll?
    // @return Boolean
    public function isGraded(Student $student)
    {
        // a student can't be graded twice
        foreach ($this->enrolled as $currentStudent) {
            if ($currentStudent->student_id === $student->id) {
                return true;
            }
        }
        return false;
    }

    public function gradeStudent(Student $student, $grade)
    {
        $student_id = $student->id;
        return $this->enrolled()->create(compact(
            'student_id',
            'grade'
        ));
    }

    /**
     * @param App\Module $module
     * @param string $semester
     * @return boolean
     */
    public static function addCourse($module, $semester)
    {
        if (Course::where('module_id', '=', $module->id)
            ->where('semester', '=', $semester)
            ->get()->isNotEmpty()) {
            return false;
        } else {
            Course::create([
                'module_id' => $module->id,
                'semester' => $semester
            ]);
            return true;
        }
    }
}
