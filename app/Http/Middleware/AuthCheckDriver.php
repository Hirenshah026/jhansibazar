<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;
use DB;
class AuthCheckDriver
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
        if(!Session::has('doctor'))
        {
            // return redirect('/drivers/login');
            //return route('login');drivers
            return redirect('/signin-emp-emp');
        }
        if(Session::has('doctor'))
        {
            $empinfo=DB::table('medical_emp_doc')->where('id',Session::get('doctor')->id)->get();
            Session::put('doctor', $empinfo[0]);
            //dd(Session::get('doctor'));
            if(Session::get('doctor')->role_id=='' || Session::get('doctor')->role_id==0)
            {
                return redirect('/signin-emp-role');
            }
            if(Session::get('doctor')->name=='' || Session::get('doctor')->email=='' || Session::get('doctor')->address=='')
            {
                // dd(Session::get('doctor'));
                return redirect('/emp-create-profile');
            }
            if(Session::get('doctor')->otp_status=='' || Session::get('doctor')->otp_status==0 )
            {
                // dd(Session::get('doctor'));
                return redirect('/signin-emp-emp/mobile');
            }
        }
        $response=$next($request);
        //dd($response);
        return $response->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma','no-cache')
        ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
        
    }
}
