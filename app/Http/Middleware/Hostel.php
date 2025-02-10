<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class Hostel
{
    public function handle($request, Closure $next)
    {        
        if (Auth::guard('hostel')->user()){
            return $next($request);
        }
        return redirect()->route('hostel.login');
    }
}
