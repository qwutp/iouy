<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Game;
use App\Models\ReviewLike;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Game $game)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);
        
        // Check if user already has a review for this game
        $existingReview = Review::where('user_id', auth()->id())
            ->where('game_id', $game->id)
            ->first();
            
        if ($existingReview) {
            return back()->with('error', 'Вы уже оставили отзыв на эту игру. Вы можете отредактировать свой существующий отзыв.');
        }
        
        Review::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'rating' => $request->rating,
            'content' => $request->content,
        ]);
        
        return back()->with('success', 'Отзыв успешно добавлен.');
    }
    
    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Review $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);
        
        $review->update([
            'rating' => $request->rating,
            'content' => $request->content,
        ]);
        
        return back()->with('success', 'Отзыв успешно обновлен.');
    }
    
    /**
     * Remove the specified review from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review $review)
    {
        // Check if the review belongs to the authenticated user or user is admin
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $review->delete();
        
        return back()->with('success', 'Отзыв успешно удален.');
    }
    
    /**
     * Like a review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Review $review)
    {
        // Check if user already liked this review
        $existingLike = ReviewLike::where('user_id', auth()->id())
            ->where('review_id', $review->id)
            ->first();
            
        if (!$existingLike) {
            ReviewLike::create([
                'user_id' => auth()->id(),
                'review_id' => $review->id,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'likes_count' => $review->likes()->count(),
        ]);
    }
    
    /**
     * Unlike a review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlike(Review $review)
    {
        ReviewLike::where('user_id', auth()->id())
            ->where('review_id', $review->id)
            ->delete();
            
        return response()->json([
            'success' => true,
            'likes_count' => $review->likes()->count(),
        ]);
    }
}
