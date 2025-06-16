<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
  
    public function index()
    {
        $wishlistItems = auth()->user()->wishlistItems()->with('game.images')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }
  
    public function add(Game $game)
    {
        $existingItem = auth()->user()->wishlistItems()->where('game_id', $game->id)->first();
        
        if (!$existingItem) {
            auth()->user()->wishlistItems()->create([
                'game_id' => $game->id
            ]);
        }
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Игра добавлена в список желаемого');
    }
    
    public function remove(WishlistItem $wishlistItem)
    {
        if ($wishlistItem->user_id !== auth()->id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'Вы не можете удалить этот товар');
        }
        
        $wishlistItem->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Игра удалена из списка желаемого');
    }
   
    public function removeByGame($gameId)
    {
        $wishlistItem = auth()->user()->wishlistItems()->where('game_id', $gameId)->first();
        
        if (!$wishlistItem) {
            return response()->json(['success' => false, 'message' => 'Игра не найдена в списке желаемого'], 404);
        }
        
        $wishlistItem->delete();
        
        return response()->json(['success' => true]);
    }
 
    public function getCount()
    {
        $count = auth()->user()->wishlistItems()->count();
        return response()->json(['count' => $count]);
    }
}
