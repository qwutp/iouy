@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Вход в личный кабинет</h1>
            
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST" class="auth-form">
                @csrf
                
                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="auth-form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="auth-form-checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Запомнить меня</label>
                </div>
                
                <button type="submit" class="auth-form-submit">Войти</button>
            </form>
            
            <div class="auth-links">
                <a href="{{ route('password.request') }}">Забыли пароль?</a>
                <span class="auth-links-divider">|</span>
                <a href="{{ route('register') }}">Регистрация</a>
            </div>
        </div>
    </div>
@endsection
