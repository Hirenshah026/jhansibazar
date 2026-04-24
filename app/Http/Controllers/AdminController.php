<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        $shops = DB::table('shops')->get();
        return view('admin.front.dashboard',compact('shops'));
    }
    public function add_shop()
    {
        $shops = DB::table('shops')->get();
        return view('admin.front.shop_add',compact('shops'));
    }
    
    public function showPreview($id) {
        $shop  = DB::table('shops')->where('id', $id)->first();
        return view('admin.front.id_card_preview', compact('shop'));
    }

    public function downloadPreview($id) {
        $shop  = DB::table('shops')->where('id', $id)->first();
        // Isme hum ek variable bhejenge 'autoPrint'
        return view('admin.front.id_card_preview', compact('shop'))->with('autoPrint', true);
    }

    public function showLogin() {
        if (Session::has('key')) {
            return redirect('/admin/dashboard'); // Agar pehle se login hai toh dashboard bhejo
        }
        return view('admin.auth.login');
    }

    // 2. Login Handle Karna
    public function login(Request $request) {
       
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        
        return redirect('/admin/dashboard');

        // Agar galat hai toh wapas bhejo error ke sath
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function shop_store(Request $request)
    {
        // 1. Validation logic
        $validated = $request->validate([
            'shop_name'  => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone'      => 'required|digits:10',
            'pin'        => 'required|digits:4',
            'address'    => 'required|string',
            'open_time'  => 'required',
            'close_time' => 'required',
            'is_whatsapp'=> 'required|boolean',
            'off_days'   => 'nullable|array',
        ]);

        try {
            // 2. Database insertion using DB Facade
            DB::table('shops')->insert([
                'registration_id' => 'JB-' . date( 'Y' ) . '-' . mt_rand( 1000, 9999 ),
                'shop_name'  => $request->shop_name,
                'owner_name' => $request->owner_name,
                'phone'      => $request->phone,
                'pin'        => $request->pin, // Security ke liye encrypt karein
                'address'    => $request->address,
                'open_time'  => $request->open_time,
                'close_time' => $request->close_time,
                'is_whatsapp'=> $request->is_whatsapp,
                // Array ko string ya json mein convert karna padega DB ke liye
                'off_days'   => $request->has('off_days') ? json_encode($request->off_days) : null,
                
            ]);

            // 3. AJAX ke liye Success Response
            return response()->json([
                'status' => 'success',
                'message' => 'Merchant registered successfully!'
            ], 200);

        } catch (\Exception $e) {
           
            return response()->json([
                'status' => 'error',
                'message' => 'Kuch technical issue hai, dobara try karein.',
                'mm'=>$e->getMessage()
            ], 500);
        }
    }

    // 3. Logout Karna
    public function logout(Request $request) {
        Session::flush();
        return redirect('/admin/login');
    }
}
