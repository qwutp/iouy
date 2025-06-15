<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $wishlistItems = auth()->user()->wishlistItems()->with('game.images')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }
    
    /**
     * Add a game to the user's wishlist.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function add(Game $game)
    {
        // Check if the game is already in the wishlist
        $existingItem = auth()->user()->wishlistItems()->where('game_id', $game->id)->first();
        
        if (!$existingItem) {
            // Add the game to the wishlist
            auth()->user()->wishlistItems()->create([
                'game_id' => $game->id
            ]);
        }
        
        // Check if the request is AJAX
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Игра добавлена в список желаемого');
    }
    
    /**
     * Remove a game from the user's wishlist.
     *
     * @param  \App\Models\WishlistItem  $wishlistItem
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function remove(WishlistItem $wishlistItem)
    {
        // Check if the wishlist item belongs to the authenticated user
        if ($wishlistItem->user_id !== auth()->id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'Вы не можете удалить этот товар');
        }
        
        // Remove the wishlist item
        $wishlistItem->delete();
        
        // Check if the request is AJAX
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Игра удалена из списка желаемого');
    }
    
    /**
     * Remove a game from the user's wishlist by game ID.
     *
     * @param  int  $gameId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function removeByGame($gameId)
    {
        // Find the wishlist item by game ID
        $wishlistItem = auth()->user()->wishlistItems()->where('game_id', $gameId)->first();
        
        if (!$wishlistItem) {
            return response()->json(['success' => false, 'message' => 'Игра не найдена в списке желаемого'], 404);
        }
        
        // Remove the wishlist item
        $wishlistItem->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Get the count of items in the user's wishlist.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount()
    {
        $count = auth()->user()->wishlistItems()->count();
        return response()->json(['count' => $count]);
    }
}
