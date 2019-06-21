<?php

namespace App\Exceptions;

class CourseHandlingException extends \Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::error('Could not handle courses.');
    }
}
