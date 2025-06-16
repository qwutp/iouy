<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('game.images')->get();
        
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
    
    public function add(Game $game)
    {
        $existingItem = auth()->user()->cartItems()->where('game_id', $game->id)->first();
        
        if (!$existingItem) {
            auth()->user()->cartItems()->create([
                'game_id' => $game->id
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Игра добавлена в корзину'
        ]);
    }
    
    public function remove(CartItem $cartItem)
    {
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
    
    public function getCount()
    {
        $count = auth()->user()->cartItems()->count();
        return response()->json(['count' => $count]);
    }
    
    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('game')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста');
        }
        
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
