<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'release_date' => 'date',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
    ];

    /**
     * Get the images for the game.
     */
    public function images()
    {
        return $this->hasMany(GameImage::class);
    }

    /**
     * Get the primary image for the game.
     */
    public function primaryImage()
    {
        return $this->hasOne(GameImage::class)->orderBy('id', 'asc');
    }

    /**
     * Get the genres for the game.
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * Get the reviews for the game.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the cart items for the game.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the wishlist items for the game.
     */
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Get the purchase items for the game.
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Check if the game is on discount.
     *
     * @return bool
     */
    public function isOnDiscount()
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    /**
     * Get the discount percentage.
     *
     * @return int
     */
    public function getDiscountPercentage()
    {
        if (!$this->isOnDiscount()) {
            return 0;
        }

        return round(($this->price - $this->discount_price) / $this->price * 100);
    }

    /**
     * Get the average rating of the game.
     *
     * @return float|null
     */
    public function getAverageRating()
    {
        $reviews = $this->reviews;
        
        if ($reviews->isEmpty()) {
            return null;
        }
        
        return round($reviews->avg('rating'), 1);
    }

    /**
     * Get the reviews count of the game.
     *
     * @return int
     */
    public function getReviewsCount()
    {
        return $this->reviews()->count();
    }

    /**
     * Get the average rating attribute.
     *
     * @return float|null
     */
    public function getAverageRatingAttribute()
    {
        return $this->getAverageRating();
    }

    /**
     * Get the reviews count attribute.
     *
     * @return int
     */
    public function getReviewsCountAttribute()
    {
        return $this->getReviewsCount();
    }
}
