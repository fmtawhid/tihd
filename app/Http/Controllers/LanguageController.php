<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class LanguageController extends Controller
{
    public function switch($language)
    {
        app()->setLocale($language);

        session()->put('locale', $language);
        $dir = 'ltr';
        if (in_array($language, ['ar', 'dv', 'ff', 'ur', 'he', 'ku', 'fa'])) {
            $dir = 'rtl';
        }
        setlocale(LC_TIME, $language);

        Carbon::setLocale($language);
        session()->put('dir',  $dir); 
        return redirect('/');
    }
}
