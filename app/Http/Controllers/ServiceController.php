<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    
    public function list_show()
    {
        return view('front.services.list_show'); // Aapki blade file ka path
    }

    public function getServices() {
        // Yahan 'shop_id' filter bhi laga sakte ho agar multi-vendor hai
        $services = DB::table('services')
                    ->orderBy('id', 'desc')
                    ->get();
        return response()->json($services);
    }

    // API: Fetch Single Service for Edit Modal
    public function fetch($id) {
        $service = DB::table('services')->where('id', $id)->first();
        return response()->json($service);
    }

    // API: Update Service
    public function update(Request $request, $id) {
        try {
            DB::table('services')->where('id', $id)->update([
                'item_name'        => $request->item_name,
                'mrp_price'        => $request->mrp_price,
                'service_duration' => $request->service_duration,
                'updated_at'       => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Service updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // API: Delete Service
    public function destroy($id) {
        DB::table('services')->where('id', $id)->delete();
        return response()->json(['status' => 'success']);
    }
    public function bulkStore(Request $request)
    {
        // 1. Data receive check
        $servicesData = $request->input('services');

        if (empty($servicesData)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Bhai, koi data hi nahi mila!'
            ]);
        }

        // 2. Database Transaction Start (Safety ke liye)
        DB::beginTransaction();

        try {
            // Maan lete hain ki shop_id session ya auth se aa rahi hai
            // Agar aapke paas static hai toh direct '1' likh sakte hain
            $shopId = Auth::user()->shop_id ?? 1; 

            foreach ($servicesData as $item) {
                
                // Discount logic: Agar discount price hai toh is_discount_on = 1
                $mrp = (float)$item['price'];
                $discount = (float)$item['discount'];
                $hasDiscount = ($discount > 0) ? 1 : 0;

                // DB Table 'services' mein insert
                DB::table('services')->insert([
                    'shop_id'           => $shopId,
                    'item_name'         => $item['name'],
                    'description'       => null, // Optional description
                    'category'          => $item['category'],
                    'mrp_price'         => $mrp,
                    'discount_price'    => $discount,
                    'is_discount_on'    => $hasDiscount,
                    'stock_status'      => 'available',
                    'photos'            => null,
                    'tags'              => null,
                    'special_offer_text'=> null,
                    'is_spin_wheel'     => 0,
                    'service_duration'  => $item['duration'] ?? 0,
                    'salon_cat'         => strtolower($item['gender']), // men/women/unisex
                    'status'            => 'active', // Direct active status
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ]);
            }

            // Sab sahi raha toh commit kar do
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Saara data successfully save ho gaya hai!'
            ]);

        } catch (\Exception $e) {
            // Agar koi error aaya toh rollback (kuch bhi save nahi hoga)
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}