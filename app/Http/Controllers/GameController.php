<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;

class GameController extends Controller
{
 
    public function index(Request $request)
    {
        $query = Game::with(['primaryImage', 'genres', 'reviews']);
        
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
        
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        
        $query->orderBy($sortBy, $sortOrder);
        
        $games = $query->paginate(15);
        $genres = Genre::all();
        
        foreach ($games as $game) {
            $game->average_rating = $game->getAverageRating();
            $game->reviews_count = $game->getReviewsCount();
        }
        
        return view('games.index', compact('games', 'genres'));
    }

    public function show(Game $game)
    {
        $game->load(['images', 'genres', 'reviews.user']);
        
        $game->average_rating = $game->getAverageRating();
        $game->reviews_count = $game->getReviewsCount();
        
        $reviews = $game->reviews()->with('user')->get();
        
        return view('games.show', compact('game', 'reviews'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        
        $games = collect();
        
        if (!empty($query)) {
            $games = Game::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->with(['primaryImage', 'genres', 'reviews'])
                ->paginate(12);
            
            foreach ($games as $game) {
                $game->average_rating = $game->getAverageRating();
                $game->reviews_count = $game->getReviewsCount();
            }
        }
        
        $userWishlistItems = [];
        $userCartItems = [];
        
        if (auth()->check()) {
            $userWishlistItems = auth()->user()->wishlistItems()->pluck('game_id')->toArray();
            $userCartItems = auth()->user()->cartItems()->pluck('game_id')->toArray();
        }
        
        return view('games.search', compact('games', 'query', 'userWishlistItems', 'userCartItems'));
    }
}
