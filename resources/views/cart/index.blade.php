@extends('layouts.app')

@section('content')
<style>
    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .cart-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .cart-empty {
        text-align: center;
        padding: 3rem 0;
    }
    
    .cart-empty-message {
        font-size: 1.25rem;
        color: #666;
        margin-bottom: 1.5rem;
    }
    
    .btn-continue-shopping {
        background: linear-gradient(135deg, #393A43, #2c2d35);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-continue-shopping:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(57, 58, 67, 0.3);
    }
    
    .cart-items {
        margin-bottom: 2rem;
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        background-color: #f8f8f8;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .cart-item-image {
        width: 100px;
        height: 60px;
        border-radius: 0.25rem;
        overflow: hidden;
        background-color: #e0e0e0;
    }
    
    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .cart-item-details {
        display: flex;
        flex-direction: column;
    }
    
    .cart-item-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .cart-item-title a {
        color: #333;
        text-decoration: none;
    }
    
    .cart-item-title a:hover {
        color: #393A43;
    }
    
    .cart-item-price {
        font-size: 1.125rem;
        font-weight: 600;
        color: #393A43;
    }
    
    .cart-item-old-price {
        font-size: 0.875rem;
        text-decoration: line-through;
        color: #888;
        margin-right: 0.5rem;
    }
    
    .cart-item-actions {
        display: flex;
        align-items: center;
    }
    
    .btn-remove-item {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 1.25rem;
        transition: color 0.3s;
    }
    
    .btn-remove-item:hover {
        color: #bd2130;
    }
    
    .cart-summary {
        background-color: #f8f8f8;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }
    
    .cart-summary-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .cart-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .cart-summary-row:last-child {
        border-bottom: none;
    }
    
    .cart-summary-label {
        color: #666;
    }
    
    .cart-summary-value {
        font-weight: 600;
    }
    
    .cart-summary-total {
        font-size: 1.25rem;
        color: #393A43;
    }
    
    .btn-checkout {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #393A43, #2c2d35);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 1.125rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        margin-top: 1.5rem;
    }
    
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(57, 58, 67, 0.3);
    }
    
    .placeholder-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        color: #666;
        font-size: 14px;
    }

    .btn-back {
        background: linear-gradient(135deg, #393A43, #2c2d35);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(57, 58, 67, 0.3);
    }
    
    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 80px 1fr;
        }
        
        .cart-item-actions {
            grid-column: span 2;
            justify-content: flex-end;
            margin-top: 1rem;
        }
    }
</style>

<div class="cart-container">
    <div class="cart-header">
        <h1 class="cart-title">Корзина</h1>
        <button onclick="history.back()" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Назад
        </button>
    </div>
    
    @if($cartItems->isEmpty())
        <div class="cart-empty">
            <p class="cart-empty-message">Ваша корзина пуста</p>
            <a href="{{ route('games.index') }}" class="btn-continue-shopping">Перейти к играм</a>
        </div>
    @else
        <div class="cart-content">
            <div class="cart-items">
                @foreach($cartItems as $item)
                    <div class="cart-item">
                        <div class="cart-item-image">
                            @php
                                $imagePath = null;
                                if ($item->game->primaryImage) {
                                    $imagePath = $item->game->primaryImage->image_path;
                                }
                            @endphp
                            
                            @if($imagePath)
                                <img src="{{ asset('images/games/' . $imagePath) }}" alt="{{ $item->game->title }}" onerror="this.parentElement.innerHTML='<div class=\'placeholder-img\'>{{ substr($item->game->title, 0, 1) }}</div>'">
                            @else
                                <div class="placeholder-img">{{ substr($item->game->title, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="cart-item-details">
                            <h3 class="cart-item-title">
                                <a href="{{ route('games.show', $item->game) }}">{{ $item->game->title }}</a>
                            </h3>
                            <div class="cart-item-price">
                                @if($item->game->isOnDiscount())
                                    <span class="cart-item-old-price">{{ number_format($item->game->price, 0, ',', ' ') }} ₽</span>
                                    {{ number_format($item->game->discount_price, 0, ',', ' ') }} ₽
                                @else
                                    {{ number_format($item->game->price, 0, ',', ' ') }} ₽
                                @endif
                            </div>
                        </div>
                        <div class="cart-item-actions">
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-remove-item" title="Удалить из корзины">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="cart-summary">
                <h2 class="cart-summary-title">Итого</h2>
                <div class="cart-summary-row">
                    <span class="cart-summary-label">Количество товаров</span>
                    <span class="cart-summary-value">{{ $cartItems->count() }}</span>
                </div>
                <div class="cart-summary-row">
                    <span class="cart-summary-label">Сумма</span>
                    <span class="cart-summary-value cart-summary-total">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                </div>
                <a href="{{ route('cart.checkout') }}" class="btn-checkout">Оформить заказ</a>
            </div>
        </div>
    @endif
</div>
@endsection
