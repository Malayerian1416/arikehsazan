<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreviousUrlSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->method() == "GET") {
            $links = session()->has('links') ? session('links') : [];
            $currentLink = $request->path();
            array_unshift($links, $currentLink);
            session(['links' => $links]);
        }
        return $next($request);
    }
}
