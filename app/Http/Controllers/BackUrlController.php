<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class BackUrlController extends Controller
{
    public function get_back(){
        Session::put('forward',false);
        $links = Session::get("links");
        array_shift($links);
        Session::put("links",$links);
        return redirect($links[0]);
    }
}
