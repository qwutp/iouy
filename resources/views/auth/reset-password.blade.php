@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Сброс пароля</h1>
            
            <form action="{{ route('password.update') }}" method="POST" class="auth-form">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="auth-form-group">
                    <label for="password">Новый пароль</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="auth-form-group">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <button type="submit" class="auth-form-submit">Сбросить пароль</button>
            </form>
        </div>
    </div>
@endsection
