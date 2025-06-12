<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Genre;
use App\Models\CartItem;
use App\Models\WishlistItem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('allGenres', Genre::all());
        });
        
        View::composer('*', function ($view) {
            $cartCount = 0;
            $wishlistCount = 0;
            
            if (auth()->check()) {
                $cartCount = CartItem::where('user_id', auth()->id())->count();
                $wishlistCount = WishlistItem::where('user_id', auth()->id())->count();
            }
            
            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
        });
    }
}
