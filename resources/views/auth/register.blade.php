@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Регистрация</h1>
            
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
            
            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf
                
                <div class="auth-form-group">
                    <label for="name">Имя</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
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
                
                <div class="auth-form-group">
                    <label for="password_confirmation">Повторите пароль</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <button type="submit" class="auth-form-submit">Создать аккаунт</button>
            </form>
            
            <div class="auth-links">
                <span>Уже есть аккаунт?</span>
                <a href="{{ route('login') }}">Войти</a>
            </div>
        </div>
    </div>
@endsection
