<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ItemStepController extends Controller {
    public function saveStep(Request $request)
    {
        $itemId = $request->item_id;
    
        $data = [
            'shop_id'    => Session::get('shopuser')->id ?? $request->shop_id ?? 1,
            'updated_at' => now(),
        ];
    
        if ($request->has('item_name'))        $data['item_name']         = $request->item_name;
        if ($request->has('item_desc'))        $data['description']       = $request->item_desc;
        if ($request->has('category'))         $data['category']          = $request->category;
        if ($request->has('mrp_price'))        $data['mrp_price']         = $request->mrp_price;
        if ($request->has('discount_price'))   $data['discount_price']    = $request->discount_price;
        if ($request->has('discount_price'))   $data['is_discount_on']    = $request->has('discount_price') ? 1 : 0;
        if ($request->has('salon_cat'))        $data['salon_cat']         = $request->salon_cat;
        if ($request->has('service_duration')) $data['service_duration']  = $request->service_duration ?? 0;
        if ($request->has('tags'))             $data['tags']              = $request->tags;
        if ($request->has('status'))           $data['status']            = $request->status;
    
        // ── Photos: JS sends photo_1, photo_2, photo_3 as WebP blobs ──
        $photoDir = public_path('items');
        if (!file_exists($photoDir)) mkdir($photoDir, 0777, true);
    
        $paths = [];
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("photo_{$i}")) {
                $name = 'item_' . uniqid() . '.webp';
                $request->file("photo_{$i}")->move($photoDir, $name);
                $paths[] = $name;
            }
        }
        if (!empty($paths)) {
            $data['photos'] = json_encode($paths);
        }
    
        try {
            if ($itemId && $itemId != 'null') {
                DB::table('items')->where('id', $itemId)->update($data);
                $id = $itemId;
            } else {
                $data['created_at'] = now();
                $id = DB::table('items')->insertGetId($data);
            }
            return response()->json(['success' => true, 'item_id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}