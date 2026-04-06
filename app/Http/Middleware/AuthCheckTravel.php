<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
class AuthCheckTravel
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
        
        if(Session::has('doctor'))
        {
            if(Session::get('doctor')->role_id==4)
            {
                return $next($request);
            }
            
        }
        //return redirect('/signin-emp');
        // print_r($request);
        // die();
        
    }
}
