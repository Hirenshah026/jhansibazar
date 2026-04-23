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

    // 3. Logout Karna
    public function logout(Request $request) {
        Session::flush();
        return redirect('/admin/login');
    }
}
