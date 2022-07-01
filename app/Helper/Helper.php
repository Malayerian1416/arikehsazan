<?php

namespace App\Helper;

use Jenssegers\Agent\Agent;
use phpDocumentor\Reflection\Types\This;

class Helper
{
    public static function platform(){
        $agent = New Agent();
        if ($agent->isDesktop())
            return "Desktop";
        else if($agent->isPhone())
            return "Phone";
        else if($agent->isTablet())
            return "Phone";
        else if ($agent->robot())
            return view("errors/cant_detect_device");
        else
            return view("errors/cant_detect_device");
    }
}
