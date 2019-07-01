<?php

namespace App\Exceptions;

/**
 * Most likely a grade has been stored unencrypted in the db.
 */
class InvalidGradingException extends \Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::error('Invalid grading record in db.');
    }
}
