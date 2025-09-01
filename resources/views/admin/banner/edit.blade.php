@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="admin-header">
            <div class="admin-header-top">
                <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">← Назад к панели управления</a>
            </div>
            <h1 class="admin-header-title">Редактирование баннера</h1>
            <p class="admin-header-subtitle">Изменение содержимого главного баннера сайта</p>
            
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item">Главная</a>
                <a href="{{ route('admin.games') }}" class="admin-nav-item">Игры</a>
                <a href="{{ route('admin.users') }}" class="admin-nav-item">Пользователи</a>
                <a href="{{ route('admin.reviews') }}" class="admin-nav-item">Отзывы</a>
                <a href="{{ route('admin.banner.edit') }}" class="admin-nav-item active">Баннер</a>
            </nav>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="admin-content">
            <div class="admin-form">
                <h2 class="admin-section-title">Настройки баннера</h2>
                
                <form action="{{ route('admin.banner.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="admin-form-group">
                        <label for="background_image">Фоновое изображение</label>
                        <input type="file" id="background_image" name="background_image" accept="image/*">
                        @if(isset($banner) && $banner->background_image)
                            <div style="margin-top: 10px;">
                                <p style="font-size: 0.875rem; color: #666;">Текущее изображение:</p>
                                <img src="{{ asset('storage/' . $banner->background_image) }}" alt="Current banner" style="max-width: 200px; height: auto; border-radius: 4px;">
                            </div>
                        @endif
                        @error('background_image')
                            <div class="auth-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                                
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">Сохранить изменения</button>
                        <a href="{{ route('admin.dashboard') }}" class="admin-btn">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
