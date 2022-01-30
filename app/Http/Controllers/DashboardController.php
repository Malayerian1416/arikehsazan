<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function DeviceCheck(){
        $agent = new Agent();
        if ($agent->isDesktop())
            return redirect("/Dashboard/Desktop/");
        else if($agent->isPhone() || $agent->isTablet())
            return redirect("/Dashboard/Phone/");
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
    }
}
