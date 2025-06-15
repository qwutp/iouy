<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('game.images')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->game->isOnDiscount() ? $item->game->discount_price : $item->game->price;
        });
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Add a game to the user's cart.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function add(Game $game)
    {
        // Check if the game is already in the cart
        $existingItem = auth()->user()->cartItems()->where('game_id', $game->id)->first();
        
        if (!$existingItem) {
            // Add the game to the cart
            auth()->user()->cartItems()->create([
                'game_id' => $game->id
            ]);
        }
        
        // Check if the request is AJAX
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Игра добавлена в корзину');
    }
    
    /**
     * Remove a game from the user's cart.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function remove(CartItem $cartItem)
    {
        // Check if the cart item belongs to the authenticated user
        if ($cartItem->user_id !== auth()->id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'Вы не можете удалить этот товар');
        }
        
        // Remove the cart item
        $cartItem->delete();
        
        // Check if the request is AJAX
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Игра удалена из корзины');
    }
    
    /**
     * Remove a game from the user's cart by game ID.
     *
     * @param  int  $gameId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function removeByGame($gameId)
    {
        // Find the cart item by game ID
        $cartItem = auth()->user()->cartItems()->where('game_id', $gameId)->first();
        
        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Игра не найдена в корзине'], 404);
        }
        
        // Remove the cart item
        $cartItem->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Show the checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('game')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->game->isOnDiscount() ? $item->game->discount_price : $item->game->price;
        });
        
        return view('cart.checkout', compact('cartItems', 'total'));
    }
     public function getCount()
    {
        $count = auth()->user()->cartItems()->count();
        return response()->json(['count' => $count]);
    }
}
