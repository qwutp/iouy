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
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'discount_price',
        'system_requirements',
        'recommended_requirements',
        'is_featured',
        'is_new',
        'is_on_sale',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_on_sale' => 'boolean',
    ];

    /**
     * Get the current price of the game.
     *
     * @return float
     */
    public function getCurrentPrice()
    {
        return $this->discount_price ?? $this->price;
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
     * @return int|null
     */
    public function getDiscountPercentage()
    {
        if ($this->isOnDiscount()) {
            return round((1 - ($this->discount_price / $this->price)) * 100);
        }
        
        return null;
    }

    /**
     * Get the images for the game.
     */
    public function images()
    {
        return $this->hasMany(GameImage::class)->orderBy('order');
    }

    /**
     * Get the primary image for the game.
     */
    public function primaryImage()
    {
        return $this->hasOne(GameImage::class)->where('is_primary', true);
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
     * Get the average rating for the game.
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
     * Get the primary image URL for the game.
     *
     * @return string
     */
    public function getPrimaryImageUrl()
    {
        $primaryImage = $this->primaryImage;
        
        if ($primaryImage && $primaryImage->image_path) {
            return asset('images/games/' . $primaryImage->image_path);
        }
        
        return asset('images/placeholder-game.jpg');
    }

    /**
     * Get all image URLs for the game.
     *
     * @return array
     */
    public function getImageUrls()
    {
        return $this->images->map(function ($image) {
            return asset('images/games/' . $image->image_path);
        })->toArray();
    }
}
