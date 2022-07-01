<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Session;

class PreviousUrlSession
{
    public function handle(Request $request, Closure $next)
    {
        Session::has('forward') ? Session::get('forward') : Session::put('forward',true);
        $forward = Session::get('forward');
        if ($request->method() == "GET" && $forward) {
            $links = Session::has('links') ? Session::get('links') : [];
            $currentLink = $request->getRequestUri();
            if ($request->getRequestUri() == "/Dashboard/Desktop" || $request->getRequestUri() == "/Dashboard/Phone"){
                $links = [];
            }
            else {
                if ($currentLink == $links[0])
                    array_shift($links);
            }
            array_unshift($links, $currentLink);
            Session::put('links', $links);
        }
        Session::put('forward',true);
        return $next($request);
    }
}
