<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Game;
use Illuminate\Http\Request;

class CartController extends Controller
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
     * Display the user's cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('game.primaryImage')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->game->getCurrentPrice();
        });
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Add a game to the user's cart.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Game $game)
    {
        $user = auth()->user();
        
        // Check if the game is already in the cart
        $existingItem = CartItem::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->first();
            
        if (!$existingItem) {
            CartItem::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
            ]);
        }
        
        return back()->with('success', 'Game added to cart.');
    }
    
    /**
     * Remove a game from the user's cart.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(CartItem $cartItem)
    {
        // Check if the cart item belongs to the authenticated user
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }
        
        $cartItem->delete();
        
        return back()->with('success', 'Game removed from cart.');
    }
    
    /**
     * Show the checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        $cartItems = auth()->user()->cartItems()->with('game.primaryImage')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->game->getCurrentPrice();
        });
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        return view('cart.checkout', compact('cartItems', 'total'));
    }
}
