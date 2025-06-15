@extends('layouts.app')

@section('content')
    <div class="settings-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.5rem; margin: 0;">Настройки профиля</h1>
            <a href="{{ route('profile.show') }}" class="btn-back">Назад</a>
        </div>
        
        <div class="settings-card">
            <h2 class="settings-title">Основная информация</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="form-submit">Сохранить изменения</button>
            </form>
        </div>
        
        <div class="settings-card">
            <h2 class="settings-title">Изменить пароль</h2>
            
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="current_password">Текущий пароль</label>
                    <input type="password" id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Новый пароль</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <button type="submit" class="form-submit">Изменить пароль</button>
            </form>
        </div>
    </div>
@endsection
