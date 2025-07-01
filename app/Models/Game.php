<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'discount_price',
        'developer',
        'publisher',
        'release_date',
        'system_requirements',
        'recommended_requirements',
        'is_featured',
        'is_new',
        'is_bestseller',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(GameImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(GameImage::class)->orderBy('id', 'asc');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function isOnDiscount()
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    public function getDiscountPercentage()
    {
        if (!$this->isOnDiscount()) {
            return 0;
        }

        return round(($this->price - $this->discount_price) / $this->price * 100);
    }

    public function getAverageRating()
    {
        $reviews = $this->reviews;
        
        if ($reviews->isEmpty()) {
            return null;
        }
        
        return round($reviews->avg('rating'), 1);
    }

    public function getReviewsCount()
    {
        return $this->reviews()->count();
    }

    public function getAverageRatingAttribute()
    {
        return $this->getAverageRating();
    }

    public function getReviewsCountAttribute()
    {
        return $this->getReviewsCount();
    }
}
