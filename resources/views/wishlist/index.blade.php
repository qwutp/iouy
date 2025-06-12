@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 class="page-title" style="margin-bottom: 0; margin-top: 50px;">Список желаемого</h1>
            <a href="{{ route('profile.show') }}" class="back-btn">← Назад</a>
        </div>
        
        @if($wishlistItems->isEmpty())
            <div class="empty-state">
                <h3>Ваш список желаемого пуст</h3>
                <p>Добавьте игры в список желаемого, чтобы они появились здесь</p>
                <a href="{{ route('home') }}" class="empty-link">Перейти в магазин</a>
            </div>
        @else
            <div class="items-grid">
                @foreach($wishlistItems as $item)
                    <div class="item-card">
                        <div class="item-image">
                            @if($item->game->primaryImage)
                                <img src="{{ asset('storage/' . $item->game->primaryImage->image_path) }}" alt="{{ $item->game->title }}">
                            @else
                                <img src="/placeholder.svg?height=150&width=200" alt="{{ $item->game->title }}">
                            @endif
                        </div>
                        <div class="item-content">
                            <h3 class="item-title">
                                <a href="{{ route('games.show', $item->game) }}">{{ $item->game->title }}</a>
                            </h3>
                            <div class="item-price">
                                @if($item->game->isOnDiscount())
                                    {{ number_format($item->game->discount_price, 0, ',', ' ') }} ₽
                                @else
                                    {{ number_format($item->game->price, 0, ',', ' ') }} ₽
                                @endif
                            </div>
                            <div class="item-actions">
                                <form action="{{ route('cart.add', $item->game) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="item-btn item-btn-primary">В корзину</button>
                                </form>
                                <form action="{{ route('wishlist.remove', $item) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="item-btn item-btn-danger">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
