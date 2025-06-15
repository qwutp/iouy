<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the games.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Game::with(['primaryImage', 'genres', 'reviews']);
        
        // Apply filters
        if ($request->has('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genres.id', $request->genre);
            });
        }
        
        if ($request->has('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price)
                  ->orWhere('discount_price', '>=', $request->min_price);
            });
        }
        
        if ($request->has('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price)
                  ->orWhere('discount_price', '<=', $request->max_price);
            });
        }
        
        if ($request->has('on_sale') && $request->on_sale) {
            $query->whereNotNull('discount_price');
        }
        
        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        
        $query->orderBy($sortBy, $sortOrder);
        
        $games = $query->paginate(15);
        $genres = Genre::all();
        
        // Add ratings to games
        foreach ($games as $game) {
            $game->average_rating = $game->getAverageRating();
            $game->reviews_count = $game->getReviewsCount();
        }
        
        return view('games.index', compact('games', 'genres'));
    }
    
    /**
     * Display the specified game.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\View\View
     */
    public function show(Game $game)
    {
        $game->load(['images', 'genres', 'reviews.user', 'reviews.likes']);
        
        // Add ratings to game
        $game->average_rating = $game->getAverageRating();
        $game->reviews_count = $game->getReviewsCount();
        
        // Get the reviews for the game
        $reviews = $game->reviews()->with('user', 'likes')->get();
        
        return view('games.show', compact('game', 'reviews'));
    }
    
    /**
     * Search for games.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        
        $games = collect();
        
        if (!empty($query)) {
            $games = Game::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->with(['primaryImage', 'genres', 'reviews'])
                ->paginate(12);
            
            // Add ratings to games
            foreach ($games as $game) {
                $game->average_rating = $game->getAverageRating();
                $game->reviews_count = $game->getReviewsCount();
            }
        }
        
        // Get user's wishlist and cart items
        $userWishlistItems = [];
        $userCartItems = [];
        
        if (auth()->check()) {
            $userWishlistItems = auth()->user()->wishlistItems()->pluck('game_id')->toArray();
            $userCartItems = auth()->user()->cartItems()->pluck('game_id')->toArray();
        }
        
        return view('games.search', compact('games', 'query', 'userWishlistItems', 'userCartItems'));
    }
}
