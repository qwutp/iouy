<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('game.images')->get();
        
        // Calculate total price
        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->game->isOnDiscount()) {
                $total += $item->game->discount_price;
            } else {
                $total += $item->game->price;
            }
        }
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Add a game to the user's cart.
     */
    public function add(Game $game)
    {
        // Check if the game is already in the cart
        $existingItem = auth()->user()->cartItems()->where('game_id', $game->id)->first();
        
        if (!$existingItem) {
            // Add the game to the cart (without quantity field)
            auth()->user()->cartItems()->create([
                'game_id' => $game->id
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Игра добавлена в корзину'
        ]);
    }
    
    /**
     * Remove a game from the user's cart.
     */
    public function remove(CartItem $cartItem)
    {
        // Check if the cart item belongs to the authenticated user
        if ($cartItem->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Нет доступа'
            ], 403);
        }
        
        $cartItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Игра удалена из корзины'
        ]);
    }
    
    /**
     * Remove a game from the user's cart by game ID.
     */
    public function removeByGame($gameId)
    {
        $cartItem = auth()->user()->cartItems()->where('game_id', $gameId)->first();
        
        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Игра не найдена в корзине'
            ]);
        }
        
        $cartItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Игра удалена из корзины'
        ]);
    }
    
    /**
     * Get the count of items in the user's cart.
     */
    public function getCount()
    {
        $count = auth()->user()->cartItems()->count();
        return response()->json(['count' => $count]);
    }
    
    /**
     * Show the checkout page.
     */
    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('game')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста');
        }
        
        // Calculate total for checkout
        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->game->isOnDiscount()) {
                $total += $item->game->discount_price;
            } else {
                $total += $item->game->price;
            }
        }
        
        return view('cart.checkout', compact('cartItems', 'total'));
    }
}
