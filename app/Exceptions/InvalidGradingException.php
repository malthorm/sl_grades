<?php

namespace App\Exceptions;

class InvalidGradingException extends \Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::error('Invalid grading record.');
    }
}
