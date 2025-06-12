<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $featuredGames = Game::where('is_featured', true)
            ->with(['primaryImage', 'genres'])
            ->take(5)
            ->get();
            
        $newGames = Game::where('is_new', true)
            ->with(['primaryImage', 'genres'])
            ->take(5)
            ->get();
            
        $saleGames = Game::where('is_on_sale', true)
            ->with(['primaryImage', 'genres'])
            ->take(5)
            ->get();
            
        $banner = Banner::first();
        
        return view('home', compact('featuredGames', 'newGames', 'saleGames', 'banner'));
    }
}
