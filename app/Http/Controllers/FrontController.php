<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Shop;
use Illuminate\Support\Carbon;
use Session;
use App\Models\ShopReview;

class FrontController extends Controller
{
    public function storeReview(Request $request)
    {
        $request->validate([
            'shop_id'        => 'required|exists:shops,id',
            'reviewer_name'  => 'required|string|max:100',
            'reviewer_phone' => 'required|string|max:20',
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string|max:1000',
        ]);

        $getUserDetail = DB::table('users')->where('mobile',$request->reviewer_phone)->get();
        if($getUserDetail->count() > 0)
        {
            DB::table('users')->where('id', $getUserDetail[0]->id)->increment('freeSpin', 1);
        }
     
        \App\Models\ShopReview::create([
            'shop_id'        => $request->shop_id,
            'reviewer_name'  => $request->reviewer_name,
            'reviewer_phone' => $request->reviewer_phone,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);
     
        return response()->json(['success' => true, 'message' => 'Review saved!']);
    }
    // ─────────────────────────────────────────────
    // Home
    // ─────────────────────────────────────────────
    public function home()
    {
        $shops = Shop::with('reviews')           // ← eager load reviews
            ->get()
            ->map(function ($shop) {

                // Open/Closed
                $now = Carbon::now()->format('H:i:s');
                $shop->is_open = ($shop->open_time && $shop->close_time)
                    ? ($now >= $shop->open_time && $now <= $shop->close_time)
                    : null;

                // Categories
                $shop->cats = is_array($shop->categories)
                    ? $shop->categories
                    : (json_decode($shop->categories, true) ?? explode(',', $shop->categories));

                // Offers
                $shop->offers_list = is_array($shop->offers)
                    ? $shop->offers
                    : (json_decode($shop->offers, true) ?? []);

                // Item photos
                $shop->photos_list = is_array($shop->item_photos)
                    ? $shop->item_photos
                    : (json_decode($shop->item_photos, true) ?? []);

                // ⭐ Rating from reviews
                $shop->avg_rating    = $shop->reviews->avg('rating') ?? 0;
                $shop->review_count  = $shop->reviews->count();

                return $shop;
            });

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

        // ── Determine spins_left based on login status ──
        $spinsLeft = 1; // default for guests
        $isFollowed = false;
        if (Session::has('public_user')) {
            $user = Session::get('public_user');
            if ($user) {
                // Fresh fetch from DB to get latest freeSpin value
                $freshUser = DB::table('users')->where('id', $user->id)->first();
                $spinsLeft = $freshUser->freeSpin ?? 0;
                $checkFollow = DB::table('follows')->where('following_id',$freshUser->id)->where('follower_id',$shop->id)->get();
                $isFollowed = $checkFollow->count() > 0 ? true : false;
            }
        }

        // Attach spins_left to shop object so view can use $shop->spins_left
        $shop = (object) array_merge((array) $shop, ['spins_left' => $spinsLeft]);
        return view('front.spin1', compact('shop','isFollowed'));
    }

    public function decrementSpin(Request $request)
    {
        if (!Session::has('public_user')) {
            return response()->json(['success' => false, 'message' => 'Not logged in', 'spinsLeft' => 0]);
        }

        $user = Session::get('public_user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found', 'spinsLeft' => 0]);
        }

        $freshUser = DB::table('users')->where('id', $user->id)->first();

        if (!$freshUser || $freshUser->freeSpin <= 0) {
            return response()->json(['success' => false, 'message' => 'No spins left', 'spinsLeft' => 0]);
        }

        // Decrement freeSpin by 1
        DB::table('users')
            ->where('id', $user->id)
            ->decrement('freeSpin');

        $newCount = $freshUser->freeSpin - 1;

        return response()->json(['success' => true, 'spinsLeft' => $newCount]);
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
        $shopId =Session::get('shopuser')->id ?? 0;
        $shop = DB::table('shops')->where('id', $shopId)->first();
        $services = DB::table('services')->get();
        $stat = DB::table('shop_stats')
        ->where('shop_id', $shopId)
        ->select([
            DB::raw('SUM(profile_visits) as profile_visits'),
            DB::raw('SUM(regular_customer) as regular_customer'),
            DB::raw('SUM(repeat_customer) as repeat_customer'),
            DB::raw('SUM(offer_display) as offer_display')
        ])
        ->first();
        // dd($stats);
        return view('front.account', compact('shop', 'services','stat'));
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

    public function shopprofile_details(Request $request, $slug)
    {
        $ipAddress = $request->ip();
        $slug = str_replace('-', ' ', $slug);
        $shop = DB::table('shops')->where('shop_name', $slug)->first();
        if (!$shop) return redirect('/');

        $shopId    = $shop->id ?? 0;
        $services  = DB::table('services')->where('shop_id', $shopId)->orderBy('id', 'DESC')->get();
        $items     = DB::table('items')->where('shop_id', $shopId)->get();
        $result    = (new UserController)->trackActivity($shopId, 'profile_visits', $ipAddress);
        $followCount = DB::table('follows')->where('following_id', $shopId)->get();

        $shopSlug = str_replace(' ', '-', strtolower($shop->shop_name));

        // Pick best image: shop_photo (Cloudinary URL) → fallback
        $ogImage = !empty($shop->shop_photo)
            ? $shop->shop_photo
            : asset('logo/logo_listee.png');

        // Pick best description: description → tagline → fallback
        $metaDesc = !empty($shop->description)
            ? \Str::limit(strip_tags($shop->description), 155)
            : (!empty($shop->tagline)
                ? \Str::limit($shop->tagline, 155)
                : $shop->shop_name . ' ki profile Jhansi Bazaar par dekhein — services, items aur offers.');

        $seo = [
            // Basic
            'title'       => $shop->shop_name . ' — Jhansi Bazaar',
            'description' => $metaDesc,
            'keywords'    => $shop->shop_name . ', ' . $shop->categories . ', jhansi bazaar, local shop, jhansi',
            'canonical'   => url('/shopprofile-details/' . $shopSlug),
            'robots'      => 'index, follow',

            // Open Graph
            'og_title'       => $shop->shop_name . ' — Jhansi Bazaar',
            'og_description' => $metaDesc,
            'og_image'       => $ogImage,
            'og_url'         => url('/shopprofile-details/' . $shopSlug),
            'og_type'        => 'business.business',
            'og_site_name'   => 'Jhansi Bazaar',
            'og_locale'      => 'hi_IN',
        ];

        $shopSlug = str_replace(' ', '-', strtolower($shop->shop_name));
        $shopUrl  = url('/shopprofile-details/' . $shopSlug);

        $shareText = "*{$shop->shop_name}* - Jhansi Bazaar par dekhein!\n\n";

        if (!empty($shop->tagline)) {
            $shareText .= "{$shop->tagline}\n\n";
        }
        if (!empty($shop->address)) {
            $shareText .= "Pata: {$shop->address}\n";
        }
        if (!empty($shop->open_time) && !empty($shop->close_time)) {
            $shareText .= "Samay: " . date('h:i A', strtotime($shop->open_time)) . " - " . date('h:i A', strtotime($shop->close_time)) . "\n\n";
        }
        $shareText .= "Profile dekhein:\n" . $shopUrl;

        $whatsappShareUrl = "https://wa.me/?text=" . rawurlencode($shareText);

        $reviews = \App\Models\ShopReview::where('shop_id', $shop->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $isFollowed = false;
        if (session()->has('public_user')) {
            $userId = session('public_user')->id ?? 0;
            $isFollowed = \DB::table('follows')   // adjust table name to yours
                ->where('following_id', $shop->id)
                ->where('follower_id', $userId)
                ->exists();
        }

        return view('front.product_details', compact('shop', 'services', 'items', 'followCount', 'seo','whatsappShareUrl','reviews','isFollowed'));
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