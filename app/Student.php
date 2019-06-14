<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // use \App\Traits\Encryptable;

    protected $guarded = [];
    // protected $encryptable = [
    //     'uni_identifier'
    // ];


    public static function findByUniIdentifier(string $uniIdentifier)
    {
        return Student::all()->filter(function ($record) use ($uniIdentifier) {
            $field = $record->uni_identifier;
            if (decrypt($field) === $uniIdentifier) {
                return $record;
            }
        })->first();
    }

    /**
     * Get all gradings for the students.
     */
    public function grades()
    {
        return $this->hasMany(Grading::class);
    }

    // remove all info on student-> was passiert mit den versuchen?
}
