<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('front.user.dashboard');    }
    public function ajaxLogin(Request $request)
    {
        // User ko mobile aur pin se dhundo
        $user = DB::table('users')
                    ->where('mobile', $request->mobile)
                    ->where('pin', $request->pin)
                    ->first();

        if ($user) {
            // Session me data save karo
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            
            return response()->json('1'); // Success code
        }

        return response()->json('0'); // Error code
    }

    // --- FOLLOW / UNFOLLOW LOGIC ---
    public function toggleFollow(Request $request)
    {
        $follower_id = Session::get('public_user')->id??0; // Logged in user
        $following_id = $request->user_id;      // Target user/shop ID

        // Check if user is logged in
        if (!$follower_id) {
            return response()->json(['status' => 'error', 'msg' => 'Pehle login karein!']);
        }

        // Check karo pehle se follow hai ya nahi
        $followCheck = DB::table('follows')
                        ->where('follower_id', $follower_id)
                        ->where('following_id', $following_id)
                        ->first();

        if ($followCheck) {
            // Agar pehle se follow hai, toh Unfollow (Delete)
            DB::table('follows')
                ->where('follower_id', $follower_id)
                ->where('following_id', $following_id)
                ->delete();

            return response()->json(['status' => 'unfollowed']);
        } else {
            // Agar follow nahi hai, toh Follow (Insert)
            DB::table('follows')->insert([
                'follower_id'  => $follower_id,
                'following_id' => $following_id,
                'created_at'   => now(), // Manual timestamp
                'updated_at'   => now()  // Manual timestamp
            ]);

            return response()->json(['status' => 'followed']);
        }
    }
    public function save_mobile(Request $request) 
    {
        $request->validate(['mobile' => 'required']);

        $user = DB::table('users')->where('mobile' , $request->mobile)->first();
        if(!$user)
        {
            DB::table('users')->insert([
                'mobile' => $request->mobile,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $user = DB::table('users')->where('mobile' , $request->mobile)->first();
        Session::put('public_user',$user);

        return response()->json(['success' => true]);
    

    }
    public function trackActivity($shopId, $column) 
    {
        $userId = Session::get('public_user'); 

        if (!$userId) return;

        // 1. Pehle check karo kya ye combination (user + shop) pehle se exist karta hai?
        $exists = DB::table('shop_stats')
                    ->where('shop_id', $shopId)
                    ->where('user_id', $userId)
                    ->exists();

        if ($exists) {
            // 2. Agar hai, toh sirf us column ko +1 karo aur update time badlo
            DB::table('shop_stats')
                ->where('shop_id', $shopId)
                ->where('user_id', $userId)
                ->increment($column, 1, ['updated_at' => now()]);
        } else {
            // 3. Agar naya hai, toh insert kar do
            DB::table('shop_stats')->insert([
                'shop_id'    => $shopId,
                'user_id'    => $userId,
                $column      => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}