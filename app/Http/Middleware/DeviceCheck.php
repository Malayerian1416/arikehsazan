<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class DeviceCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $agent = new Agent();
        if ($agent->isDesktop())
            return redirect("/Dashboard/Desktop/");
        else if($agent->isPhone())
            return view("phone_dashboard/p_dashboard");
        else
            return view("home");
    }
}
