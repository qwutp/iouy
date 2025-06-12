@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <h1 class="auth-title">Восстановление пароля</h1>
            
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <form action="{{ route('password.email') }}" method="POST" class="auth-form">
                @csrf
                
                <div class="auth-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="auth-form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="auth-form-submit">Отправить ссылку для сброса пароля</button>
            </form>
            
            <div class="auth-links">
                <a href="{{ route('login') }}">Вернуться к входу</a>
            </div>
        </div>
    </div>
@endsection
