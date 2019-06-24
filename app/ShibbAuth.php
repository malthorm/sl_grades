<?php

namespace App;

class ShibbAuth
{
    //if not env(debug)
    public static function isAuthenticated()
    {
        if (env('APP_DEBUG')) {
            return true;
        }
        return array_key_exists('REMOTE_USER', $_SERVER) ? true : false;
    }

    public static function authenticate()
    {
        return view('login');
    }


    public static function authorize(string $affiliation)
    {
        if (env('APP_DEBUG')) {
            return true;
        }

        $affiliations = ShibbAuth::getShibAffiliations();
        if (!is_array($affiliations)) {
            return false;
        }
        if ($affiliation === "mitarbeiter") {
            $affiliation = $affiliation . '@tu-chemnitz.de';


            return in_array($affiliation, ShibbAuth::getShibAffiliations())
                 ? true : false;
        } elseif ($affiliation === "student") {
            $affiliation = $affiliation . '@tu-chemnitz.de';
            return in_array($affiliation, ShibbAuth::getShibAffiliations())
             ? true : false;
        } else {
            return false;
        }
    }

    public static function getShibAffiliations()
    {
        if (array_key_exists('HTTP_SHIB_EP_AFFILIATION', $_SERVER)) {
            $affiliations = strtolower(htmlspecialchars($_SERVER['HTTP_SHIB_EP_AFFILIATION']));
            return explode(';', $affiliations);
        }
    }
}
