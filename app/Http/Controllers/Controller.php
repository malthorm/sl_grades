<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function isAuthenticated()
    {
        return array_key_exists('REMOTE_USER', $_SERVER) ? true : false;
    }

    public function authenticate()
    {
        return view('login');
    }


    public function authorize(string $affiliation)
    {
        $affiliations = $this->getShibAffiliations();
        if (!is_array($affiliations)) {
            return false;
        }
        if ($affiliation === "mitarbeiter") {
            $affiliation = $affiliation . '@tu-chemnitz.de';
            // if (e($_SERVER['REMOTE_USER']) === 'malth'){
            //     return true;
            // }
            in_array($affiliation, $this->getShibAffiliations()) ? true : false;
        } elseif ($affiliation === "student") {
            $affiliation = $affiliation . '@tu-chemnitz.de';
            in_array($affiliation, $this->getShibAffiliations()) ? true : false;
        } else {
            return false;
        }
    }

    private function getShibAffiliations()
    {
        if (array_key_exists('HTTP_SHIB_EP_AFFILIATION', $_SERVER)) {
            $affiliations = strtolower(htmlspecialchars($_SERVER['HTTP_SHIB_EP_AFFILIATION']));
            return explode(';', $affiliations);
        }
    }
}
