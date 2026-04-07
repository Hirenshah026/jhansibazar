<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Session;

class ItemStepController extends Controller
{
    public function saveStep(Request $request)
    {
        $itemId = $request->item_id;

        $data = [
            'shop_id'    => Session::get('shopuser')->id ?? $request->shop_id ?? 1,
            'updated_at' => now(),
        ];

        if ($request->has('item_name'))        $data['item_name']        = $request->item_name;
        if ($request->has('item_desc'))        $data['description']      = $request->item_desc;
        if ($request->has('category'))         $data['category']         = $request->category;
        if ($request->has('mrp_price'))        $data['mrp_price']        = $request->mrp_price;
        if ($request->has('discount_price'))   $data['discount_price']   = $request->discount_price;
        if ($request->has('discount_price'))   $data['is_discount_on']   = 1;
        if ($request->has('salon_cat'))        $data['salon_cat']        = $request->salon_cat;
        if ($request->has('service_duration')) $data['service_duration'] = $request->service_duration ?? 0;
        if ($request->has('tags'))             $data['tags']             = $request->tags;
        if ($request->has('status'))           $data['status']           = $request->status;

        // ── Photos: upload photo_1, photo_2, photo_3 to Cloudinary 'items' folder ──
        $newPhotos = [];

        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("photo_{$i}")) {
                $uploaded = Cloudinary::upload(
                    $request->file("photo_{$i}")->getRealPath(),
                    [
                        'folder'         => 'items',
                        'transformation' => [
                            'quality'      => 'auto',
                            'fetch_format' => 'auto',
                        ],
                    ]
                );

                $newPhotos[] = [
                    'url'       => $uploaded->getSecurePath(),
                    'public_id' => $uploaded->getPublicId(),
                ];
            }
        }

        // ── If updating, merge new photos with existing ones ──
        if (!empty($newPhotos)) {
            if ($itemId && $itemId != 'null') {
                $existing = DB::table('items')->where('id', $itemId)->value('photos');
                $existing = json_decode($existing ?? '[]', true) ?? [];

                // If old photos were stored as plain filenames (old format), clear them
                // and just use new Cloudinary format
                $isOldFormat = !empty($existing) && isset($existing[0]) && is_string($existing[0]);

                if ($isOldFormat) {
                    // Old local file format — replace entirely with new Cloudinary photos
                    $merged = $newPhotos;
                } else {
                    // Already Cloudinary format — merge
                    $merged = array_merge($existing, $newPhotos);
                }
            } else {
                $merged = $newPhotos;
            }

            $data['photos'] = json_encode(array_values($merged));
        }

        // ── Save to DB ──
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

    // ─────────────────────────────────────────────
    // Delete a single photo from an item
    // ─────────────────────────────────────────────
    public function deletePhoto(Request $request)
    {
        $request->validate([
            'item_id'   => 'required',
            'public_id' => 'required|string',
        ]);

        $item = DB::table('items')->where('id', $request->item_id)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Delete from Cloudinary
        Cloudinary::destroy($request->public_id);

        // Remove from JSON array
        $photos = json_decode($item->photos ?? '[]', true) ?? [];
        $photos = array_filter($photos, fn($p) => 
            is_array($p) && $p['public_id'] !== $request->public_id
        );

        DB::table('items')->where('id', $request->item_id)->update([
            'photos'     => json_encode(array_values($photos)),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Photo deleted']);
    }

    // ─────────────────────────────────────────────
    // Delete entire item + all its Cloudinary photos
    // ─────────────────────────────────────────────
    public function deleteItem($id)
    {
        $item = DB::table('items')->where('id', $id)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Delete all photos from Cloudinary
        $photos = json_decode($item->photos ?? '[]', true) ?? [];
        foreach ($photos as $photo) {
            if (is_array($photo) && !empty($photo['public_id'])) {
                Cloudinary::destroy($photo['public_id']);
            }
        }

        DB::table('items')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Item deleted']);
    }
}