@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="admin-header">
            <div class="admin-header-top">
                <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">← Назад к панели управления</a>
            </div>
            <div class="admin-header-main">
                <h1 class="admin-header-title">Управление отзывами</h1>
            </div>
            <p class="admin-header-subtitle">Модерация отзывов пользователей</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="admin-content">
            @if($reviews->count() > 0)
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Игра</th>
                            <th>Оценка</th>
                            <th>Отзыв</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>{{ $review->user->name }}</td>
                                <td>
                                    <a href="{{ route('games.show', $review->game) }}" style="color: #393A43; text-decoration: underline;">{{ $review->game->title }}</a>
                                </td>
                                <td>
                                    <div class="admin-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="admin-star {{ $i <= $review->rating ? 'active' : '' }}">★</span>
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ Str::limit($review->content, 50) }}
                                    </div>
                                </td>
                                <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="admin-table-actions">
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот отзыв?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-table-btn admin-table-btn-delete">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="admin-pagination">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="admin-empty-state">
                    <h3>Здесь еще нет отзывов</h3>
                    <p>Отзывы появятся после того, как пользователи начнут оценивать игры</p>
                </div>
            @endif
        </div>
    </div>
@endsection
