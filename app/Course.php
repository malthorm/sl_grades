<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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


    // @return Boolean
    public function isGraded(String $uni_identifier)
    {
        // a student can't be graded twice
        foreach ($this->gradings as $grading) {
            $match = $grading->decryptUniIdentifier(true);
            $currentStudent = Student::find($grading->student_id);
            if ($match === $uni_identifier) {
                return true;
            }
        }
        return false;
    }

    public function gradeStudent(Student $student, $grade)
    {
        $student_id = $student->id;
        $grade = encrypt($grade);
        return $this->gradings()->create(compact(
            'student_id',
            'grade'
        ));
    }

    // USED?
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
