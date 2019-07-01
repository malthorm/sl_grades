<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signInAdmin()
    {
        $_SERVER['REMOTE_USER'] = 'testAdmin';
        $_SERVER['HTTP_SHIB_EP_AFFILIATION'] =
            'Student@tu-chemnitz.de;student@tu-chemnitz.de;Mitarbeiter@tu-chemnitz.de';
        $_SERVER['admins'] = 'testAdmin;testAdmin2';
    }

    public function signInStudent()
    {
        $_SERVER['REMOTE_USER'] = 'testStudent';
        $_SERVER['HTTP_SHIB_EP_AFFILIATION'] =
            'Student@tu-chemnitz.de;student@tu-chemnitz.de';
        $_SERVER['admins'] = 'testAdmin;testAdmin2';
    }
}
