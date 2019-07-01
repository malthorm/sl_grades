<?php

namespace App\Exceptions;

/**
 * Most likely a uni_identifer has been stored unencrypted in the db.
 */
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
