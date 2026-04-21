<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Session;

class ShopController extends Controller
 {
    // ─────────────────────────────────────────────
    // Step 1: Owner & Shop Timings
    // ─────────────────────────────────────────────

    public function saveStep1( Request $request )
 {
        $request->validate( [
            'owner_name' => 'required|string|max:255',
            'shop_name'  => 'required|string|max:255',
            'phone'      => 'required|string|max:15',
        ] );

        $open_time  = $request->open_time  ? date( 'H:i:s', strtotime( $request->open_time ) )  : null;
        $close_time = $request->close_time ? date( 'H:i:s', strtotime( $request->close_time ) ) : null;

        $data = [
            'owner_name'  => $request->owner_name,
            'shop_name'   => $request->shop_name,
            'phone'       => $request->phone,
            'is_whatsapp' => $request->wa_check   ?? 0,
            'address'     => $request->address,
            'open_time'   => $open_time,
            'close_time'  => $close_time,
            'off_days'    => $request->off_days,
            'pin'    => $request->pin,
            'pin_set'    => 1,
            'updated_at'  => now(),
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

    // ─────────────────────────────────────────────
    // Step 2: Categories, Offers & Payments
    // ─────────────────────────────────────────────

    public function saveStep2( Request $request )
 {
        $request->validate( [
            'shop_id' => 'required',
        ] );

        DB::table( 'shops' )->where( 'id', $request->shop_id )->update( [
            'categories'    => $request->categories,
            'tagline'       => $request->tagline,
            'description'   => $request->description,
            'payment_modes' => $request->payment_modes,
            'offers'        => $request->offers,
            'updated_at'    => now(),
        ] );

        return response()->json( [ 'success' => true ] );
    }

    // ─────────────────────────────────────────────
    // Step 3: All Photos & Finalize
    // ─────────────────────────────────────────────

    public function finalSubmit( Request $request )
 {
        $request->validate( [
            'shop_id'      => 'required',
            'shop_photo'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'owner_photo'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'item_photo_1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'item_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'item_photo_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'item_photo_4' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'item_photo_5' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'item_photo_6' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ] );

        // Fetch existing shop record
        $shop = DB::table( 'shops' )->where( 'id', $request->shop_id )->first();

        if ( !$shop ) {
            return response()->json( [ 'success' => false, 'message' => 'Shop not found' ], 404 );
        }

        $updateData = [
            'status'          => 'pending',
            'registration_id' => 'JB-' . date( 'Y' ) . '-' . mt_rand( 1000, 9999 ),
            'updated_at'      => now(),
        ];

        // ── Shop photo → 'shops' folder ──────────────
        if ( $request->hasFile( 'shop_photo' ) ) {

            // Delete old one from Cloudinary
            if ( !empty( $shop->shop_photo_public_id ) ) {
                Cloudinary::destroy( $shop->shop_photo_public_id );
            }

            $uploaded = Cloudinary::upload(
                $request->file( 'shop_photo' )->getRealPath(),
                [
                    'folder'         => 'shops',
                    'transformation' => [ 'quality' => 'auto', 'fetch_format' => 'auto' ],
                ]
            );

            $updateData[ 'shop_photo' ]           = $uploaded->getSecurePath();
            $updateData[ 'shop_photo_public_id' ] = $uploaded->getPublicId();
        }

        // ── Owner photo → 'owners' folder ────────────
        if ( $request->hasFile( 'owner_photo' ) ) {

            // Delete old one from Cloudinary
            if ( !empty( $shop->owner_photo_public_id ) ) {
                Cloudinary::destroy( $shop->owner_photo_public_id );
            }

            $uploaded = Cloudinary::upload(
                $request->file( 'owner_photo' )->getRealPath(),
                [
                    'folder'         => 'owners',
                    'transformation' => [ 'quality' => 'auto', 'fetch_format' => 'auto' ],
                ]
            );

            $updateData[ 'owner_photo' ]           = $uploaded->getSecurePath();
            $updateData[ 'owner_photo_public_id' ] = $uploaded->getPublicId();
        }

        // ── Item photos → 'items' folder ─────────────
        $existingItems = json_decode( $shop->item_photos ?? '[]', true ) ?? [];
        $items = $existingItems;
        // keep existing uploads

        for ( $i = 1; $i <= 6; $i++ ) {
            $key = 'item_photo_' . $i;

            if ( $request->hasFile( $key ) ) {

                // Delete old item photo at this index
                if ( !empty( $existingItems[ $i - 1 ][ 'public_id' ] ) ) {
                    Cloudinary::destroy( $existingItems[ $i - 1 ][ 'public_id' ] );
                }

                $uploaded = Cloudinary::upload(
                    $request->file( $key )->getRealPath(),
                    [
                        'folder'         => 'items',
                        'transformation' => [ 'quality' => 'auto', 'fetch_format' => 'auto' ],
                    ]
                );

                $items[ $i - 1 ] = [
                    'url'       => $uploaded->getSecurePath(),
                    'public_id' => $uploaded->getPublicId(),
                ];
            }
        }

        if ( !empty( $items ) ) {
            $updateData[ 'item_photos' ] = json_encode( array_values( $items ) );
        }

        DB::table( 'shops' )->where( 'id', $request->shop_id )->update( $updateData );
        $user_detail_new = DB::table( 'shops' )->where( 'id', $request->shop_id??0 )->first();

        Session::put( 'shopuser', $user_detail_new );

        return response()->json( [
            'success' => true,
            'reg_id'  => $updateData[ 'registration_id' ],
        ] );
    }

    // ─────────────────────────────────────────────
    // Delete a single item photo
    // ─────────────────────────────────────────────

    public function deleteItemPhoto( Request $request )
 {
        $request->validate( [
            'shop_id'   => 'required',
            'public_id' => 'required|string',
        ] );

        $shop  = DB::table( 'shops' )->where( 'id', $request->shop_id )->first();

        if ( !$shop ) {
            return response()->json( [ 'success' => false, 'message' => 'Shop not found' ], 404 );
        }

        // Delete from Cloudinary
        Cloudinary::destroy( $request->public_id );

        // Remove from JSON array
        $items = json_decode( $shop->item_photos ?? '[]', true ) ?? [];
        $items = array_filter( $items, fn( $p ) => $p[ 'public_id' ] !== $request->public_id );

        DB::table( 'shops' )->where( 'id', $request->shop_id )->update( [
            'item_photos' => json_encode( array_values( $items ) ),
            'updated_at'  => now(),
        ] );

        return response()->json( [ 'success' => true, 'message' => 'Photo deleted' ] );
    }

    // ─────────────────────────────────────────────
    // Delete entire shop + all Cloudinary assets
    // ─────────────────────────────────────────────

    public function deleteShop( $id )
 {
        $shop = DB::table( 'shops' )->where( 'id', $id )->first();

        if ( !$shop ) {
            return response()->json( [ 'success' => false, 'message' => 'Shop not found' ], 404 );
        }

        // Delete shop photo from Cloudinary
        if ( !empty( $shop->shop_photo_public_id ) ) {
            Cloudinary::destroy( $shop->shop_photo_public_id );
        }

        // Delete owner photo from Cloudinary
        if ( !empty( $shop->owner_photo_public_id ) ) {
            Cloudinary::destroy( $shop->owner_photo_public_id );
        }

        // Delete all item photos from Cloudinary
        $items = json_decode( $shop->item_photos ?? '[]', true ) ?? [];
        foreach ( $items as $item ) {
            if ( !empty( $item[ 'public_id' ] ) ) {
                Cloudinary::destroy( $item[ 'public_id' ] );
            }
        }

        DB::table( 'shops' )->where( 'id', $id )->delete();

        return response()->json( [ 'success' => true, 'message' => 'Shop deleted' ] );
    }

    // ─────────────────────────────────────────────
    // Manage Offers
    // ─────────────────────────────────────────────

    public function manageOffers()
 {
        $shop   = DB::table( 'shops' )->where( 'id', Session::get( 'shopuser' )->id )->first();
        $offers = json_decode( $shop->offers, true ) ?? [];
        return view( 'front.offer', compact( 'shop', 'offers' ) );
    }

    public function saveOffer( Request $request )
 {
        // 1. Validation: Jo fields humne Front-end mein diye hain unhe validate karo
        $request->validate( [
            'offer_text'   => 'required|string|max:255',
            'quantity'     => 'nullable|integer|min:0',
            'expiry_date'  => 'nullable|date',
            'is_active'    => 'required|in:0,1',
            'offer_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ] );

        // Shop user session se lo
        $shopUser = Session::get( 'shopuser' );
        if ( !$shopUser ) {
            return response()->json( [ 'status' => 'error', 'message' => 'Session expired!' ], 401 );
        }

        $shop = DB::table( 'shops' )->where( 'id', $shopUser->id )->first();
        // Purane offers ko array mein convert karo
        $offers = json_decode( $shop->offers, true ) ?? [];

        // 2. Image Upload Logic
        $imageUrl = null;
        if ( $request->hasFile( 'offer_image' ) ) {
            $file = $request->file( 'offer_image' );
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Public folder mein save karega
            $file->move( public_path( 'uploads/offers' ), $fileName );
            $imageUrl = asset( 'uploads/offers/' . $fileName );
        }

        // 3. Naya Data Object taiyar karo ( Same as Front-end keys )
        $offerData = [
            'text'        => $request->offer_text,
            'quantity'    => $request->quantity ?? 0,
            'category'    => $request->category ?? null,
            'offer_description'    => $request->offer_description ?? null,
            'expiry_date' => $request->expiry_date,
            'is_active'   => ( int ) $request->is_active,
            'image'       => $imageUrl, // Initial image
        ];

        // 4. Update ya Add ka logic
        // Index check: agar index empty nahi hai to update, warna naya add
        if ( $request->filled( 'index' ) ) {
            $index = $request->index;

            // Agar edit ke time nayi image upload nahi ki, to purani wali hi rakho
            if ( !$imageUrl && isset( $offers[ $index ][ 'image' ] ) ) {
                $offerData[ 'image' ] = $offers[ $index ][ 'image' ];
            }

            $offers[ $index ] = $offerData;
        } else {
            // Naya Add karne se pehle check karo limit ( Max 5 )
            if ( count( $offers ) >= 5 ) {
                return response()->json( [
                    'status'  => 'error',
                    'message' => 'Limit Full: Aap sirf 5 offers hi laga sakte hain!'
                ], 422 );
            }

            // Agar nayi image nahi hai to default gift icon
            if ( !$imageUrl ) {
                $offerData[ 'image' ] = asset( 'assets/img/gift.png' );
            }

            $offers[] = $offerData;
        }

        // 5. Database mein Save karo ( re-index array values )
        DB::table( 'shops' )->where( 'id', $shop->id )->update( [
            'offers'     => json_encode( array_values( $offers ) ),
            'updated_at' => now(),
        ] );

        return response()->json( [ 'status' => 'success' ] );
    }

    public function deleteOffer( Request $request )
 {
        $shop   = DB::table( 'shops' )->where( 'id', Session::get( 'shopuser' )->id )->first();
        $offers = json_decode( $shop->offers, true ) ?? [];

        if ( count( $offers ) <= 3 ) {
            return response()->json( [
                'status'  => 'error',
                'message' => 'Spinner ke liye kam se kam 3 offers zaroori hain!'
            ], 422 );
        }

        unset( $offers[ $request->index ] );

        DB::table( 'shops' )->where( 'id', $shop->id )->update( [
            'offers'     => json_encode( array_values( $offers ) ),
            'updated_at' => now(),
        ] );

        return response()->json( [ 'status' => 'success' ] );
    }

    public function update( Request $request )
 {
        // 1. Validation ( Zaroori hai taaki data sahi rahe )
        $request->validate( [
            'shop_id'    => 'required|exists:shops,id',
            'shop_name'  => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'address'    => 'required|string',
            'open_time'  => 'required',
            'close_time' => 'required',
        ] );

        try {
            // 2. Database Update
            DB::table( 'shops' )
            ->where( 'id', $request->shop_id )
            ->update( [
                'shop_name'    => $request->shop_name,
                'owner_name'   => $request->owner_name,
                'tagline'      => $request->tagline,
                'address'      => $request->address,
                'open_time'    => $request->open_time,
                'close_time'   => $request->close_time,
                'status'       => $request->status,
                'updated_at'   => now(),
            ] );

            // 3. JSON Response for AJAX
            return response()->json( [
                'status'  => 'success',
                'message' => 'Shop information updated successfully!'
            ] );

        } catch ( \Exception $e ) {
            return response()->json( [
                'status'  => 'error',
                'message' => 'Kuch problem aayi hai: ' . $e->getMessage()
            ], 500 );
        }
    }
}