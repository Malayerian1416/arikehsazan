<?php

namespace App\Http\Controllers;

use Hekmatinasser\Verta\Verta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
