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
        $allGames = Game::with(['genres', 'primaryImage', 'reviews'])->get();
    
        $genres = Genre::all();
    
        $banner = Banner::first();
    
        foreach ($allGames as $game) {
            $game->average_rating = $game->getAverageRating();
            $game->reviews_count = $game->getReviewsCount();
        }
    
        $userCartItems = [];
        $userWishlistItems = [];
    
        if (auth()->check()) {
            $userCartItems = auth()->user()->cartItems()->pluck('game_id')->toArray();
            $userWishlistItems = auth()->user()->wishlistItems()->pluck('game_id')->toArray();
        }
    
        return view('home', compact('allGames', 'genres', 'banner', 'userCartItems', 'userWishlistItems'));
    }
}
