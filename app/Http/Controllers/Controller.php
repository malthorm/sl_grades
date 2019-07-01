<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function defaultView()
    {
        $authLvl = $this->authorization();
        if ($authLvl === 'student') {
            return redirect('grades');
        } elseif ($authLvl === 'admin') {
            return redirect('courses');
        } else {
            return view('login');
        }
    }

    /**
     * Aborts action if the user doesn't have the speficfied authorization level.
     * @param  string $user [authorization level (student|admin)]
     * @return \Illuminate\Http\Response [403]
     */
    protected function authorizeRequest(string $user)
    {
        if ($this->authorization() !== $user && $this->authorization() !== 'admin') {
            abort(403);
        }
    }

    /**
     * Returns the authorization level of the current user.
     * @return [string] [guest|student|admin]
     */
    protected function authorization()
    {
        if (!array_key_exists('REMOTE_USER', $_SERVER)) {
            return 'guest';
        }
        $admins = explode(';', $_SERVER['admins']);
        $neededAffiliation = 'student@tu-chemnitz.de';
        $userAffiliations = explode(';', strtolower($_SERVER['HTTP_SHIB_EP_AFFILIATION']));

        if (in_array($_SERVER['REMOTE_USER'], $admins)) {
            return 'admin';
        } elseif (in_array($neededAffiliation, $userAffiliations)) {
            return 'student';
        } else {
            return 'guest';
        }
    }
}
