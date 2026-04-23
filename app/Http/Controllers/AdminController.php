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
}
