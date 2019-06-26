<?php

namespace App;

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
        return $this->hasMany(Grading::class);
    }
}
