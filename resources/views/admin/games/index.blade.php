@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="admin-header">
            <div class="admin-header-top">
                <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">← Назад к панели управления</a>
            </div>
            <div class="admin-header-main">
                <h1 class="admin-header-title">Управление играми</h1>
                
            </div>
            <p class="admin-header-subtitle">Просмотр и управление каталогом игр</p>
            <a href="{{ route('admin.games.create') }}" class="admin-add-btn">+ Добавить игру</a>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="admin-content">
            @if($games->count() > 0)
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Скидка</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($games as $game)
                            <tr>
                                <td>{{ $game->id }}</td>
                                <td>
                                    @if($game->primaryImage)
                                        <img src="{{ asset('images/games/' . $game->primaryImage->image_path) }}" alt="{{ $game->title }}" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div style="width: 80px; height: 60px; background-color: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #666;">Нет изображения</div>
                                    @endif
                                </td>
                                <td>{{ $game->title }}</td>
                                <td>{{ number_format($game->price, 0, ',', ' ') }} ₽</td>
                                <td>
                                    @if($game->discount_price)
                                        {{ number_format($game->discount_price, 0, ',', ' ') }} ₽ (-{{ $game->getDiscountPercentage() }}%)
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="admin-table-actions">
                                        <a href="{{ route('games.show', $game) }}" class="admin-table-btn admin-table-btn-view">Просмотр</a>
                                        <a href="{{ route('admin.games.edit', $game) }}" class="admin-table-btn admin-table-btn-edit">Редактировать</a>
                                        <form action="{{ route('admin.games.destroy', $game) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту игру?')" style="display: inline;">
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
                    {{ $games->links() }}
                </div>
            @else
                <div class="admin-empty-state">
                    <h3>Здесь еще нет игр</h3>
                    <p>Добавьте первую игру в каталог, чтобы начать работу</p>
                    <a href="{{ route('admin.games.create') }}" class="admin-add-btn">+ Добавить игру</a>
                </div>
            @endif
        </div>
    </div>
@endsection
