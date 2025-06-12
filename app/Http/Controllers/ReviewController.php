<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewLike;
use App\Models\Game;
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
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        $user = auth()->user();
        
        // Check if the user has already reviewed this game
        $existingReview = Review::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->first();
            
        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this game.');
        }
        
        Review::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);
        
        return back()->with('success', 'Review submitted successfully.');
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
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        $review->update([
            'content' => $request->content,
            'rating' => $request->rating,
        ]);
        
        return back()->with('success', 'Review updated successfully.');
    }
    
    /**
     * Remove the specified review from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review $review)
    {
        // Check if the review belongs to the authenticated user or if the user is an admin
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $review->delete();
        
        return back()->with('success', 'Review deleted successfully.');
    }
    
    /**
     * Like a review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like(Review $review)
    {
        $user = auth()->user();
        
        // Check if the user has already liked this review
        $existingLike = ReviewLike::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->first();
            
        if (!$existingLike) {
            ReviewLike::create([
                'user_id' => $user->id,
                'review_id' => $review->id,
            ]);
        }
        
        return back();
    }
    
    /**
     * Unlike a review.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlike(Review $review)
    {
        $user = auth()->user();
        
        ReviewLike::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->delete();
            
        return back();
    }
}
