<?php

namespace App\Exceptions;

class InvalidStudentException extends \Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::error('Invalid Student record in db.');
    }
}
