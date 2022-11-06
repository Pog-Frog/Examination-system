<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::guard('instructor')->check()){
            return redirect()->route('instructor_dashboard');
        }else if(Auth::guard()->check()){
            return redirect()->route('student_dashboard');
        }else if(Auth::guard('admin')->check()){
            return redirect()->route('admin_dashboard');
        }

        return $next($request);
    }
}