<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API route for getting game reviews
Route::get('/games/{game}/reviews', function (\App\Models\Game $game) {
    $reviews = $game->reviews()->with(['user', 'likes'])->get();
    
    // Add likes count and whether the current user liked the review
    $reviews->each(function ($review) {
        $review->likes_count = $review->likes->count();
        $review->is_liked_by_user = auth()->check() ? $review->isLikedByUser(auth()->id()) : false;
    });
    
    return $reviews;
});
