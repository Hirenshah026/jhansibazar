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

    // ── Auto decode JSON columns ───────────────────────────────────
    protected $casts = [
        'offers'      => 'array',
        'item_photos' => 'array',
    ];

    // ── offers_list accessor → always returns a clean array ────────
    public function getOffersListAttribute(): array
    {
        $offers = $this->offers ?? [];

        // Filter: only active, not expired
        return array_values(array_filter($offers, function ($offer) {
            if (empty($offer['is_active'])) return false;

            // Check expiry if present
            if (!empty($offer['expiry_date'])) {
                try {
                    $expiry = \Carbon\Carbon::parse($offer['expiry_date'])->endOfDay();
                    if ($expiry->isPast()) return false;
                } catch (\Exception $e) {
                    // If date parse fails, keep the offer
                }
            }

            return true;
        }));
    }

    // ── photos_list accessor → always returns a clean array ────────
    public function getPhotosListAttribute(): array
    {
        return $this->item_photos ?? [];
    }

    // ── is_open accessor → true/false/null ────────────────────────
    public function getIsOpenAttribute(): ?bool
    {
        if (!$this->open_time || !$this->close_time) return null;

        try {
            $now   = \Carbon\Carbon::now();
            $open  = \Carbon\Carbon::parse($this->open_time)->setDateFrom($now);
            $close = \Carbon\Carbon::parse($this->close_time)->setDateFrom($now);

            // Handle overnight shops (e.g. 10 PM – 4 AM)
            if ($close->lt($open)) {
                $close->addDay();
            }

            return $now->between($open, $close);
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── avg_rating accessor ────────────────────────────────────────
    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // ── review_count accessor ──────────────────────────────────────
    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    // ── reviews relationship ───────────────────────────────────────
    public function reviews()
    {
        return $this->hasMany(\App\Models\ShopReview::class, 'shop_id');
    }
}