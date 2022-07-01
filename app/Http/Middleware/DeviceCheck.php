<?php

namespace App\Http\Middleware;

use App\Helper\Helper;
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

    }
}
