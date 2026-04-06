<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class AuthCheck
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
        if(!Session::has('key'))
        {
            return redirect('/admin');
            //return route('login');
        }
        if(Session::has('key'))
        {
            $wh_info = DB::table('whatsapp_account_shopowner')->where('shopkeeper_id',Session::get('key')->id)->first();
            Session::put('whatsapp_info', $wh_info);
            $shopkeeperId = Session::get('key')->whatsapp_account_id??0; 

            $hasActivePlan = DB::table('subscriptions_purchased')
                ->where('shopkeeper_whatsapp_acc_id', $shopkeeperId)
                ->whereDate('expiry_date', '>=', date('Y-m-d'))
                ->exists(); 

            if (!$hasActivePlan) 
            {
                // return redirect('/plans');
            } 
        }
        //return $next($request);
        $response=$next($request);
        //dd($response);
        return $response->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
