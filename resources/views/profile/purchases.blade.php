@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 class="page-title" style="margin-bottom: 0;">История покупок</h1>
            <a href="{{ route('profile.show') }}" class="item-btn item-btn-secondary">← Назад</a>
        </div>
        
        @if($purchases->isEmpty())
            <div class="empty-state">
                <h3>У вас пока нет покупок</h3>
                <p>Ваши покупки будут отображаться здесь</p>
                <a href="{{ route('home') }}" class="empty-link">Перейти в магазин</a>
            </div>
        @else
            <div class="items-grid">
                @foreach($purchases as $purchase)
                    @foreach($purchase->items as $item)
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
                                <div class="item-price">{{ number_format($item->price, 0, ',', ' ') }} ₽</div>
                                <p style="font-size: 0.75rem; color: var(--color-secondary); margin-top: 0.5rem;">
                                    Куплено: {{ $purchase->created_at->format('d.m.Y') }}
                                </p>
                                <div class="item-actions">
                                    <a href="{{ route('games.show', $item->game) }}" class="item-btn item-btn-primary">Подробнее</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @endif
    </div>
@endsection
