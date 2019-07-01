<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = [];


    /**
     * Get the courses for the module.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
