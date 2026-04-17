<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Shop;
use Illuminate\Support\Carbon;
use Session;

class FrontController extends Controller
{
    // ─────────────────────────────────────────────
    // Home
    // ─────────────────────────────────────────────
    public function home()
    {
        $shops = Shop::get()
            ->map(function ($shop) {
                // Parse open/close status
                $now = Carbon::now()->format('H:i:s');
                $shop->is_open = ($shop->open_time && $shop->close_time)
                    ? ($now >= $shop->open_time && $now <= $shop->close_time)
                    : null;

                // Parse categories (stored as text/JSON/comma-separated)
                $shop->cats = is_array($shop->categories)
                    ? $shop->categories
                    : json_decode($shop->categories, true) ?? explode(',', $shop->categories);

                // Parse offers
                $shop->offers_list = is_array($shop->offers)
                    ? $shop->offers
                    : (json_decode($shop->offers, true) ?? []);

                // Parse item_photos
                $shop->photos_list = is_array($shop->item_photos)
                    ? $shop->item_photos
                    : (json_decode($shop->item_photos, true) ?? []);

                return $shop;
            });

        
        // Group by first category for sections like in your screenshot
        $shopsByCategory = $shops->groupBy(function ($shop) {
            return $shop->cats[0] ?? 'other';
        });
        return view('front.index', compact('shops','shopsByCategory'));
    }

    // ─────────────────────────────────────────────
    // Delete Item + Cloudinary Photos
    // ─────────────────────────────────────────────
    public function deleteItem($id)
    {
        $item = DB::table('items')
            ->where('id', $id)
            ->where('shop_id', Session::get('shopuser')->id)
            ->first();

        if (!$item) abort(403);

        $photos = json_decode($item->photos ?? '[]', true) ?? [];

        foreach ($photos as $photo) {
            if (is_array($photo) && !empty($photo['public_id'])) {
                Cloudinary::destroy($photo['public_id']);
            } elseif (is_string($photo)) {
                $path = public_path('items/' . ltrim($photo, '/'));
                if (file_exists($path)) unlink($path);
            }
        }

        DB::table('items')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    // ─────────────────────────────────────────────
    // Spin
    // ─────────────────────────────────────────────
    public function spin($slug = 'none')
    {
        $slug = str_replace('-', ' ', $slug);
        $shop = DB::table('shops')->where('shop_name', $slug)->first();

        if (!$shop) {
            $shops = DB::table('shops')->get();
            return view('spin_avail', compact('shops'));
        }

        return view('front.spin1', compact('shop'));
    }

    // ─────────────────────────────────────────────
    // Rozana
    // ─────────────────────────────────────────────
    public function rozana()
    {
        return view('front.rozana');
    }

    // ─────────────────────────────────────────────
    // Account
    // ─────────────────────────────────────────────
    public function account()
    {
        $shop = DB::table('shops')->where('id', Session::get('shopuser')->id ?? 0)->first();
        $services = DB::table('services')->get();
        return view('front.account', compact('shop', 'services'));
    }

    // ─────────────────────────────────────────────
    // Wallet
    // ─────────────────────────────────────────────
    public function wallet()
    {
        return view('front.wallet');
    }

    // ─────────────────────────────────────────────
    // Shop Profile
    // ─────────────────────────────────────────────
    public function shopprofile($slug)
    {
        $slug = str_replace('-', ' ', $slug);
        $shop = DB::table('shops')->where('shop_name', $slug)->first();

        if (!$shop) return redirect('/');

        return view('front.product_show', compact('shop'));
    }

    public function shopprofile_details($slug)
    {
        $slug = str_replace('-', ' ', $slug);
        $shop = DB::table('shops')->where('shop_name', $slug)->first();

        if (!$shop) return redirect('/');
        $shopId=$shop->id??0;
        $services = DB::table('services')->where('shop_id', $shopId)->orderBy('id', 'DESC')->get();
        $items = DB::table('items')->where('shop_id', $shopId)->get();
        return view('front.product_details', compact('shop', 'services','items'));
    }

    // ─────────────────────────────────────────────
    // Auth
    // ─────────────────────────────────────────────
    public function shop_login()
    {
        return view('front.auth.login');
    }
    public function shop_set_pin()
    {
        return view('front.auth.set_pin');
    }
    public function shop_login_ajax(Request $request)
    {
        $shop = DB::table('shops')
            ->where('phone', $request->mobile)
            ->where('pin', $request->pin)
            ->first();

        if ($shop) {
            Session::put('shopuser', $shop);
            return response()->json(['success' => true, 'message' => 'Login success']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials. Please try again.']);
    }
    public function shop_updatePin(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric',
        ]);

        try {
            // Hum directly DB facade use kar rahe hain
            DB::table('shops')
                ->where('id',Session::get('shopuser')->id)
                ->update([
                    'pin' => $request->pin, // Security ke liye hash karna best hai
                    'pin_set'=>1
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'PIN successfully Set'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function shop_logout()
    {
        Session::forget('shopuser');
        Session::flush();
        return redirect('/')->with('success', 'Logged out successfully');
    }

    // ─────────────────────────────────────────────
    // Other Pages
    // ─────────────────────────────────────────────
    public function notifications()
    {
        return view('front.notifications');
    }

    public function healthcard()
    {
        return view('front.healthcard');
    }

    public function add_shop()
    {
        return view('front.add_shop');
    }

    public function add_service()
    {
        $categories = DB::table('category')->where( 'shop_id', Session::get('shopuser')->id)->orderBy('id', 'desc')->get();
        return view('front.add_services_bulk',compact('categories'));
    }

    public function add_item()
    {
        $categories = DB::table('category')->where( 'shop_id', Session::get('shopuser')->id)->orderBy('id', 'desc')->get();
        return view('front.add_services',compact('categories'));
    }
}