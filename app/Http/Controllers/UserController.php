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
    public function trackActivity($shopId, $column, $ipAddress) 
    {
        if (Session::has('public_user')) {
            $userId = Session::get('public_user')->id;
        } 
        // 2. Agar nahi, toh kya pehle se koi guest_id session me hai?
        elseif (Session::has('temp_guest_id')) {
            $userId = Session::get('temp_guest_id');
        } 
        // 3. Agar dono nahi, toh naya guest_id banao aur session me save karo
        else {
            $userId = 'guest-' . rand(100, 9999);
            Session::put('temp_guest_id', $userId);
        }
            
        if (Session::has('shop_user')) {
            $userId = null;
        }

        if (!$userId) return "no_user";

        $today = now()->toDateString(); // Aaj ki date (YYYY-MM-DD)

        // 1. Check karo kya ye user aaj is shop par pehle aa chuka hai?
        $existingRecord = DB::table('shop_stats')
                            ->where('shop_id', $shopId)
                            ->where('user_id', $userId)
                            ->whereDate('visit_date', $today)
                            ->first();

        if ($existingRecord) {
            // 2. Agar SAME user, SAME shop par AAJ hi aaya hai -> Repeat Customer +1
            DB::table('shop_stats')
                ->where('id', $existingRecord->id)
                ->increment('repeat_customer', 1, ['updated_at' => now()]);

            return "repeat_visitor_updated";
        } else {
            // 3. Agar user is shop par aaj pehli baar aaya hai -> New Profile Visit
            DB::table('shop_stats')->insert([
                'shop_id'         => $shopId,
                'user_id'         => $userId,
                'ip_address'      => $ipAddress,
                'visit_date'      => $today,
                'profile_visits'  => 1, // Pehli baar visit
                'regular_customer'=> 0,
                'repeat_customer' => 0,
                'offer_display'   => 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return "new_visit_inserted";
        }
    }
}