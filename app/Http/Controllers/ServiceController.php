<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display the bulk service entry page
     */
    public function index()
    {
        return view('services.bulk_entry'); // Aapki blade file ka path
    }

    /**
     * AJAX se data save karne ke liye function
     */
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