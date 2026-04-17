<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopReview extends Model
{
    protected $table = 'shop_reviews';

    protected $fillable = [
        'shop_id', 'reviewer_name', 'reviewer_phone', 'rating', 'comment',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}