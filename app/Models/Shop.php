<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops';

    protected $fillable = [
        'registration_id', 'owner_name', 'shop_name', 'phone',
        'is_whatsapp', 'address', 'open_time', 'close_time',
        'off_days', 'categories', 'tagline', 'description',
        'payment_modes', 'offers', 'shop_photo', 'shop_photo_public_id',
        'owner_photo', 'item_photos', 'status',
    ];

    public function reviews()
    {
        return $this->hasMany(\App\Models\ShopReview::class, 'shop_id');
    }
}