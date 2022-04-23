<?php

namespace App\Http\Controllers;

use Hekmatinasser\Verta\Verta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $agent = new Agent();
        if ($agent->isDesktop())
            $this->agent = "desktop_dashboard";
        else if($agent->isPhone() || $agent->isTablet())
            $this->agent = "phone_dashboard";
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
        return false;
    }
    public function gregorian_date_convert($date,$symbol){
        try {
            $date_array = explode($symbol,$date);
            $converted_date = Verta::getGregorian($date_array[0],$date_array[1],$date_array[2]);
            return Carbon::parse(implode("-",$converted_date)." 00:00:00")->toDateTimeString();
        }
        catch (Throwable $ex){
            abort(501);
        }
    }
}
