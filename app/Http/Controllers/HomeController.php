<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        // Get all games
        $allGames = Game::with(['genres', 'primaryImage', 'reviews'])->get();
    
        // Get all genres
        $genres = Genre::all();
    
        // Get the banner
        $banner = Banner::first();
    
        // Add ratings to games
        foreach ($allGames as $game) {
            $game->average_rating = $game->getAverageRating();
            $game->reviews_count = $game->getReviewsCount();
        }
    
        // Get user's cart and wishlist items if authenticated
        $userCartItems = [];
        $userWishlistItems = [];
    
        if (auth()->check()) {
            $userCartItems = auth()->user()->cartItems()->pluck('game_id')->toArray();
            $userWishlistItems = auth()->user()->wishlistItems()->pluck('game_id')->toArray();
        }
    
        return view('home', compact('allGames', 'genres', 'banner', 'userCartItems', 'userWishlistItems'));
    }
}
