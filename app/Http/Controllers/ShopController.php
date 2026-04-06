<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
class ShopController extends Controller {

    // Step 1: Owner & Shop Timings

    public function saveStep1( Request $request ) {

        $open_time = $request->open_time ? date( 'H:i:s', strtotime( $request->open_time ) ) : null;
        $close_time = $request->close_time ? date( 'H:i:s', strtotime( $request->close_time ) ) : null;

        $data = [
            'owner_name' => $request->owner_name,
            'shop_name'  => $request->shop_name,
            'phone'      => $request->phone,
            'is_whatsapp'=> $request->wa_check,
            'address'    => $request->address,
            'open_time'  => $open_time,
            'close_time' => $close_time,
            'off_days'   => $request->off_days,
            'updated_at' => now()
        ];
        $id = $request->shop_id ?: null;
        if ( $id ) {
            DB::table( 'shops' )->where( 'id', $id )->update( $data );
        } else {
            $data[ 'created_at' ] = now();
            $id = DB::table( 'shops' )->insertGetId( $data );
        }
        return response()->json( [ 'success' => true, 'shop_id' => $id ] );
    }

    // Step 2: Categories, Offers & Payments

    public function saveStep2( Request $request ) {
        DB::table( 'shops' )->where( 'id', $request->shop_id )->update( [
            'categories'    => $request->categories,
            'tagline'       => $request->tagline,
            'description'   => $request->description,
            'payment_modes' => $request->payment_modes,
            'offers'        => $request->offers,
            'updated_at'    => now()
        ] );
        return response()->json( [ 'success' => true ] );
    }

    // Step 3: All Photos & Finalize

    public function finalSubmit(Request $request)
    {
        $path = public_path('shop_photo');
        if (!file_exists($path)) mkdir($path, 0777, true);
    
        $updateData = [
            'status'          => 'pending',
            'registration_id' => 'JB-' . date('Y') . '-' . mt_rand(1000, 9999),
            'updated_at'      => now(),
        ];
    
        if ($request->hasFile('shop_photo')) {
            $name = 'shop_' . time() . '.webp';
            $request->file('shop_photo')->move($path, $name);
            $updateData['shop_photo'] = $name;
        }
    
        if ($request->hasFile('owner_photo')) {
            $name = 'owner_' . time() . '.webp';
            $request->file('owner_photo')->move($path, $name);
            $updateData['owner_photo'] = $name;
        }
    
        // JS sends item_photo_1 … item_photo_6 as individual fields
        $items = [];
        for ($i = 1; $i <= 6; $i++) {
            $key = 'item_photo_' . $i;
            if ($request->hasFile($key)) {
                $name = 'item_' . uniqid() . '.webp';
                $request->file($key)->move($path, $name);
                $items[] = $name;
            }
        }
        if (!empty($items)) {
            $updateData['item_photos'] = json_encode($items);
        }
    
        DB::table('shops')->where('id', $request->shop_id)->update($updateData);
    
        return response()->json([
            'success' => true,
            'reg_id'  => $updateData['registration_id'],
        ]);
    }

    public function manageOffers() {
        $shop = DB::table( 'shops' )->where( 'id', Session::get('shopuser')->id)->first();
        $offers = json_decode( $shop->offers, true ) ?? [];
        return view( 'front.offer', compact( 'shop', 'offers' ) );
    }

    public function saveOffer( Request $request ) {
        $shop = DB::table( 'shops' )->where( 'id', Session::get('shopuser')->id)->first();
        $offers = json_decode( $shop->offers, true ) ?? [];

        if ( $request->index !== null ) {
            // Update existing offer
            $offers[ $request->index ] = $request->offer_text;
        } else {
            // Add new offer ( Max 5 )
            if ( count( $offers ) >= 5 ) {
                return response()->json( [ 'status' => 'error', 'message' => 'Max 5 offers hi allow hain!' ], 422 );
            }
            $offers[] = $request->offer_text;
        }

        DB::table( 'shops' )->where( 'id', $shop->id )->update( [ 'offers' => json_encode( array_values( $offers ) ) ] );
        return response()->json( [ 'status' => 'success' ] );
    }

    public function deleteOffer( Request $request ) {
        $shop = DB::table( 'shops' )->where( 'id', Session::get('shopuser')->id)->first();
        $offers = json_decode( $shop->offers, true ) ?? [];

        if ( count( $offers ) <= 3 ) {
            return response()->json( [ 'status' => 'error', 'message' => 'Spinner ke liye kam se kam 3 offers zaroori hain!' ], 422 );
        }

        unset( $offers[ $request->index ] );
        DB::table( 'shops' )->where( 'id', $shop->id )->update( [ 'offers' => json_encode( array_values( $offers ) ) ] );
        return response()->json( [ 'status' => 'success' ] );
    }
}