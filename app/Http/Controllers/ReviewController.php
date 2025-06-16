<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Game;
use App\Models\ReviewLike;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Game $game)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);
        
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

    public function update(Request $request, Review $review)
    {
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
 
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $review->delete();
        
        return back()->with('success', 'Отзыв успешно удален.');
    }

    public function like(Review $review)
    {
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
