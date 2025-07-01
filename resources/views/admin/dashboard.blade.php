@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="admin-header">
            <h1 class="admin-header-title">Панель администратора</h1>
            <p class="admin-header-subtitle">Управление контентом и пользователями сайта</p>
            
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item active">Главная</a>
                <a href="{{ route('admin.games') }}" class="admin-nav-item">Игры</a>
                <a href="{{ route('admin.users') }}" class="admin-nav-item">Пользователи</a>
                <a href="{{ route('admin.reviews') }}" class="admin-nav-item">Отзывы</a>
                <a href="{{ route('admin.banner.edit') }}" class="admin-nav-item">Баннер</a>
            </nav>
        </div>
        
        <div class="admin-stats">
            <div class="admin-stat-card">
                <div class="admin-stat-value">{{ $gamesCount }}</div>
                <div class="admin-stat-label">Игр в каталоге</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-value">{{ $usersCount }}</div>
                <div class="admin-stat-label">Зарегистрированных пользователей</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-value">{{ $reviewsCount }}</div>
                <div class="admin-stat-label">Отзывов на игры</div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-value">0</div>
                <div class="admin-stat-label">Совершено покупок</div>
            </div>
        </div>
        
        <div class="admin-content" style="margin-top: 1rem;">
            <h2 class="admin-section-title">Быстрые действия</h2>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; max-width: 100%;">
                <a href="{{ route('admin.games.create') }}" class="admin-btn admin-btn-primary" style="padding: 1rem; text-align: center; display: block;">
                    Добавить новую игру
                </a>
                <a href="{{ route('admin.users') }}" class="admin-btn admin-btn-primary" style="padding: 1rem; text-align: center; display: block;">
                    Управление пользователями
                </a>
                <a href="{{ route('admin.reviews') }}" class="admin-btn admin-btn-primary" style="padding: 1rem; text-align: center; display: block;">
                    Модерация отзывов
                </a>
                <a href="{{ route('admin.banner.edit') }}" class="admin-btn admin-btn-primary" style="padding: 1rem; text-align: center; display: block;">
                    Изменить баннер
                </a>
            </div>
        </div>
    </div>
@endsection
