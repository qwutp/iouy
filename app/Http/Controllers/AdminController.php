<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameImage;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Banner;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Проверяем права администратора в конструкторе
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                abort(403, 'Доступ запрещен. Требуются права администратора.');
            }
            return $next($request);
        });
    }
    
    public function dashboard()
    {
        $gamesCount = Game::count();
        $usersCount = User::count();
        $reviewsCount = Review::count();
        $purchasesCount = Purchase::count();
        
        return view('admin.dashboard', compact('gamesCount', 'usersCount', 'reviewsCount', 'purchasesCount'));
    }
    
    public function games()
    {
        $games = Game::with('primaryImage')->paginate(15);
        
        return view('admin.games.index', compact('games'));
    }
    
    public function createGame()
    {
        $genres = Genre::all();
        
        return view('admin.games.create', compact('genres'));
    }
    
    public function storeGame(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'system_requirements' => 'required|string',
            'recommended_requirements' => 'required|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_on_sale' => 'boolean',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
            'images' => 'required|array|min:1|max:6',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'required|integer|min:0',
        ]);
        
        try {
            // Создаем игру
            $game = Game::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'system_requirements' => $request->system_requirements,
                'recommended_requirements' => $request->recommended_requirements,
                'is_featured' => $request->has('is_featured'),
                'is_new' => $request->has('is_new'),
                'is_on_sale' => $request->has('is_on_sale'),
            ]);
            
            // Привязываем жанры
            $game->genres()->attach($request->genres);
            
            // Создаем директорию если не существует
            $gamesDir = public_path('images/games');
            if (!file_exists($gamesDir)) {
                mkdir($gamesDir, 0755, true);
            }
            
            // Загружаем изображения
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $fileName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $image->move($gamesDir, $fileName);
                    
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => $fileName,
                        'is_primary' => $index == $request->primary_image,
                        'order' => $index,
                    ]);
                }
            }
            
            return redirect()->route('admin.games')->with('success', 'Игра успешно добавлена!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при создании игры: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function editGame(Game $game)
    {
        $game->load(['images', 'genres']);
        $genres = Genre::all();
        
        return view('admin.games.edit', compact('game', 'genres'));
    }
    
    public function updateGame(Request $request, Game $game)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'system_requirements' => 'required|string',
            'recommended_requirements' => 'required|string',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_on_sale' => 'boolean',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id',
            'primary_image' => 'nullable|exists:game_images,id',
            'new_images' => 'nullable|array|max:6',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:game_images,id',
        ]);
        
        try {
            // Update game data
            $game->update([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'system_requirements' => $request->system_requirements,
                'recommended_requirements' => $request->recommended_requirements,
                'is_featured' => $request->has('is_featured'),
                'is_new' => $request->has('is_new'),
                'is_on_sale' => $request->has('is_on_sale'),
            ]);
            
            // Sync genres
            $game->genres()->sync($request->genres);
            
            // Handle image deletion
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = GameImage::where('id', $imageId)->where('game_id', $game->id)->first();
                    
                    if ($image) {
                        $imagePath = public_path('images/games/' . $image->image_path);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        $image->delete();
                    }
                }
            }
            
            // Create directory if it doesn't exist
            $gamesDir = public_path('images/games');
            if (!file_exists($gamesDir)) {
                mkdir($gamesDir, 0755, true);
            }
            
            // Handle new images
            if ($request->hasFile('new_images')) {
                $currentImageCount = $game->images()->count();
                $newImageCount = count($request->file('new_images'));
                
                // Check if total images would exceed 6
                if ($currentImageCount + $newImageCount > 6) {
                    return back()->withErrors(['new_images' => 'Максимальное количество изображений: 6. У игры уже ' . $currentImageCount . ' изображений.'])->withInput();
                }
                
                $lastOrder = GameImage::where('game_id', $game->id)->max('order') ?? -1;
                
                foreach ($request->file('new_images') as $index => $image) {
                    $fileName = time() . '_' . ($lastOrder + $index + 1) . '.' . $image->getClientOriginalExtension();
                    $image->move($gamesDir, $fileName);
                    
                    GameImage::create([
                        'game_id' => $game->id,
                        'image_path' => $fileName,
                        'is_primary' => false,
                        'order' => $lastOrder + $index + 1,
                    ]);
                }
            }
            
            // Handle primary image change
            if ($request->has('primary_image') && $request->primary_image) {
                // Reset all images to non-primary
                GameImage::where('game_id', $game->id)->update(['is_primary' => false]);
                
                // Set the selected image as primary
                GameImage::where('id', $request->primary_image)
                    ->where('game_id', $game->id)
                    ->update(['is_primary' => true]);
            }
            
            return redirect()->route('admin.games')->with('success', 'Игра успешно обновлена!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при обновлении игры: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function destroyGame(Game $game)
    {
        foreach ($game->images as $image) {
            $imagePath = public_path('images/games/' . $image->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $game->delete();
        
        return redirect()->route('admin.games')->with('success', 'Game deleted successfully.');
    }
    
    public function users()
    {
        $users = User::paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function destroyUser(User $user)
    {
        if ($user->is_admin && $user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Вы не можете удалить свой собственный аккаунт администратора.']);
        }
        
        if ($user->avatar) {
            $avatarPath = public_path('images/avatars/' . $user->avatar);
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Пользователь удален успешно.');
    }
    
    public function reviews()
    {
        $reviews = Review::with(['user', 'game'])->paginate(15);
        
        return view('admin.reviews.index', compact('reviews'));
    }
    
    public function destroyReview(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews')->with('success', 'Review deleted successfully.');
    }
    
    public function editBanner()
    {
        $banner = Banner::first();
        
        return view('admin.banner.edit', compact('banner'));
    }
    
    public function updateBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_color' => 'required|string|max:7',
        ]);
        
        $banner = Banner::firstOrNew();
        
        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->background_color = $request->background_color;
        
        if ($request->hasFile('background_image')) {
            // Создать директорию если не существует
            $bannersDir = public_path('images/banners');
            if (!file_exists($bannersDir)) {
                mkdir($bannersDir, 0755, true);
            }
            
            // Удалить старое изображение
            if ($banner->background_image) {
                $oldPath = public_path('images/banners/' . $banner->background_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            // Сохранить новое изображение
            $fileName = time() . '.' . $request->file('background_image')->getClientOriginalExtension();
            $request->file('background_image')->move($bannersDir, $fileName);
            $banner->background_image = $fileName;
        }
        
        $banner->save();
        
        return back()->with('success', 'Баннер обновлен успешно.');
    }
}
