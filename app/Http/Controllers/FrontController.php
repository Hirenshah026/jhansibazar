<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FrontController extends Controller
{
    public function home() 
    { 
        $shops=DB::table('shops')->get();
        return view('front.index',compact('shops')); 
    }
    public function rozana() 
    { 
        return view('front.rozana'); 
    }
    
    public function deleteItem($id) {
        $item = DB::table('items')->where('id', $id)->where('shop_id', Session::get('shopuser')->id)->first();
        if (!$item) abort(403);
        // Delete photos from storage if needed
        $photos = json_decode($item->photos, true);
        if ($photos) {
            foreach ($photos as $photo) {
                $path = public_path('items/' . ltrim($photo, '/'));
                if (file_exists($path)) unlink($path);
            }
        }
        DB::table('items')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Item deleted successfully.');
    }
    
    public function spin($slug='none') 
    { 
        $slug = str_replace('-', ' ', $slug);
        $shop=DB::table('shops')->where('shop_name',$slug)->first();
        if(!$shop)
        {
             $shops=DB::table('shops')->get();
            return view('spin_avail',compact('shops')); 
        }
        return view('front.spin1',compact('shop')); 
    }
    public function account() 
    { 
        $shop=DB::table('shops')->where('id',Session::get('shopuser')->id??0)->first();
        return view('front.account',compact('shop')); 
    }
    public function wallet() { return view('front.wallet'); }
    public function shopprofile($slug) 
    { 
        $slug = str_replace('-', ' ', $slug);
        $shop=DB::table('shops')->where('shop_name',$slug)->first();
        if(!$shop)
        {
            return redirect('/');
        }
        return view('front.product_show',compact('shop')); 
    }
    public function shopprofile_details($slug) 
    { 
        $slug = str_replace('-', ' ', $slug);
        $shop=DB::table('shops')->where('shop_name',$slug)->first();
        if(!$shop)
        {
            return redirect('/');
        }
        $services = DB::table('services')->orderBy('id','DESC')->get();

        return view('front.product_details',compact('shop','services')); 
    }
    public function shop_login(Request $request)
    {
        
        return view('front.auth.login');
    }
    public function shop_login_ajax(Request $request)
    {
        $shop=DB::table('shops')->where('phone',$request->mobile)->where('pin',$request->pin)->first();
        $data=[
            'success'=>false,
            'message'=>'Invalid credentials. Please try again.'
        ];
        if($shop)
        {
            Session::put('shopuser',$shop);
            $data=[
                'success'=>true,
                'message'=>'login succes'
            ];
        }
        
        return  response()->json($data);
    }
    public function notifications() { return view('front.notifications'); } 
    public function healthcard() { return view('front.healthcard'); }
    public function add_shop() { return view('front.add_shop'); }
    public function add_service() { return view('front.add_services_bulk'); }
    public function add_item() { return view('front.add_services'); }
    public function shop_logout()
    {
        // Session khali karo
        Session::forget('shopuser');
        Session::flush();

        // Login page par redirect karo
        return redirect('/')->with('success', 'Logged out successfully');
    }
    
}
