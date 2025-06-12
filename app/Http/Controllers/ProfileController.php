<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show()
    {
        $user = auth()->user();
        $user->load(['purchases.items.game.primaryImage', 'reviews.game']);
        return view('profile.show', compact('user'));
    }
    
    public function settings()
    {
        $user = auth()->user();
        return view('profile.settings', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        return back()->with('success', 'Профиль обновлен успешно.');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Неверный текущий пароль.']);
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return back()->with('success', 'Пароль изменен успешно.');
    }
    
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = auth()->user();
        
        // Удалить старый аватар
        if ($user->avatar) {
            $oldPath = public_path('images/avatars/' . $user->avatar);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        
        // Создать директорию если не существует
        $avatarDir = public_path('images/avatars');
        if (!file_exists($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }
        
        // Сохранить новый аватар
        $fileName = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
        $request->file('avatar')->move($avatarDir, $fileName);
        
        $user->update(['avatar' => $fileName]);
        
        return back()->with('success', 'Аватар обновлен успешно.');
    }
    
    public function purchases()
    {
        $purchases = auth()->user()->purchases()->with('items.game.primaryImage')->get();
        return view('profile.purchases', compact('purchases'));
    }
}
