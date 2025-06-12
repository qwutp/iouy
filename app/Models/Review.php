<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'content',
        'rating',
    ];

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the game that owns the review.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get the likes for the review.
     */
    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    /**
     * Check if the review is liked by a user.
     *
     * @param  int  $userId
     * @return bool
     */
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
