<?php

namespace App;

use App\Module;
use App\Grading;
use Illuminate\Database\Eloquent\Model;
use App\Exception\InvalidStudentException;
use App\Exceptions\UniIdentifierException;

class Student extends Model
{
    protected $guarded = [];

    /*
     * Looks in storage for a student with the given identifier.
     * Creates a new student if it can't find one in storage.
     *
     * @param string $uniIdentifier
     * @return \App\Student
     * @throws App\Exceptions\UniIdentifierException
     */
    public static function findOrCreate(string $uniIdentifier)
    {
        if ((strlen($uniIdentifier) < 2) || (strlen($uniIdentifier) > 20)) {
            throw new UniIdentifierException('Kennung muss 2-20 Zeichen lang sein.');
        }

        $student = Student::findByUniIdentifier($uniIdentifier);
        if (!$student) {
            $id = encrypt($uniIdentifier);
            return Student::create([
                'uni_identifier' => $id
            ]);
        }
        return $student;
    }

    /*
     * Decrypts the models uni_identifier.
     * @throws App\Exceptions\InvalidStudentException
     */
    public function getPlainUniIdentifier()
    {
        try {
            return decrypt($this->uni_identifier);
        } catch (\Exception $e) {
            throw new InvalidStudentException($e);
        }
    }


    /*
     * Looks in storage for a student with the given uni_identifier.
     *
     * @param string $uniIdentifier
     * @return \App\Student
     * @throws App\Exceptions\InvalidStudentException
     */
    public static function findByUniIdentifier(string $uniIdentifier)
    {
        try {
            return Student::all()->filter(function ($record) use ($uniIdentifier) {
                if ($record->getPlainUniIdentifier() === $uniIdentifier) {
                    return $record;
                }
            })->first();
        } catch (\Exception $e) {
            throw new InvalidStudentException('Invalid Student Record: ' . $e->getMessage());
        }
    }

    /**
     * Get all gradings for the students.
     */
    public function grades()
    {
        return $this->hasMany(Grading::class)->latest();
    }

    /**
     * Checks if the grading is the latest attempt a student has made in a module.
     *
     * @param App\Module $module
     * @param App\Grading $grade
     * @return bool
     */
    public function isLatestAttemptInModule(Module $module, Grading $grade)
    {
        $attempts = $this->getAttemptsInModule($module);
        foreach ($attempts as $attempt) {
            if ($grade->id < $attempt->id) {
                return false;
            }
        }
        return true;
    }

    /**
     * Count the number of times the student was graded
     * in the same module.
     *
     * @param App\Module $module
     * @return int
     */
    public function countAttemptsInModule($module)
    {
        return count($this->getAttemptsInModule($module));
    }

    /**
     * Gets all attempts a student has made in a given module.
     *
     * @param App\Module $module
     * @param int $studentId
     * @return Illuminate\Support\Collection
     */
    protected function getAttemptsInModule(Module $module)
    {
        $attempts = array();
        foreach ($this->grades as $grade) {
            if ($grade->course->module_id == $module->id) {
                $attempts[] = $grade;
            }
        }
        return $attempts;
    }
}
