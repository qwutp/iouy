<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use App\Models\Game;
use Illuminate\Http\Request;

class WishlistController extends Controller
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
     * Display the user's wishlist.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wishlistItems = auth()->user()->wishlistItems()->with('game.primaryImage')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }
    
    /**
     * Add a game to the user's wishlist.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Game $game)
    {
        $user = auth()->user();
        
        // Check if the game is already in the wishlist
        $existingItem = WishlistItem::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->first();
            
        if (!$existingItem) {
            WishlistItem::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
            ]);
        }
        
        return back()->with('success', 'Game added to wishlist.');
    }
    
    /**
     * Remove a game from the user's wishlist.
     *
     * @param  \App\Models\WishlistItem  $wishlistItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(WishlistItem $wishlistItem)
    {
        // Check if the wishlist item belongs to the authenticated user
        if ($wishlistItem->user_id !== auth()->id()) {
            abort(403);
        }
        
        $wishlistItem->delete();
        
        return back()->with('success', 'Game removed from wishlist.');
    }
}
