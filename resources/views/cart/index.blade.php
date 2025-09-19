@extends('layouts.app')

@section('content')
<div class="cart-container">
    <div class="cart-header">
        <h1 class="cart-title">Корзина</h1>
        <button onclick="history.back()" class="btn-back">
            Назад
        </button>
    </div>
    
    @if($cartItems->isEmpty())
        <div class="cart-empty">
            <p class="cart-empty-message">Ваша корзина пуста</p>
            <a href="{{ route('home') }}" class="btn-continue-shopping">Перейти к играм</a>
        </div>
    @else
        <div class="cart-content">
            <div class="cart-items">
                @foreach($cartItems as $item)
                    <div class="cart-item" id="cart-item-{{ $item->id }}">
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
                            
                            @if($item->game->isOnDiscount())
                                <div class="cart-item-discount">-{{ $item->game->getDiscountPercentage() }}%</div>
                            @endif
                        </div>
                        <div class="cart-item-content">
                            <h3 class="cart-item-title">
                                <a href="{{ route('games.show', $item->game) }}">{{ $item->game->title }}</a>
                            </h3>
                            <div class="cart-item-genres">
                                @foreach($item->game->genres->take(2) as $genre)
                                    <span class="cart-item-genre">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                            
                            @php
                                $averageRating = $item->game->reviews->avg('rating') ?? 0;
                                $reviewsCount = $item->game->reviews->count();
                            @endphp
                            
                            @if($reviewsCount > 0)
                                <div class="rating">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="rating-star {{ $i <= round($averageRating) ? 'active' : '' }}">★</span>
                                        @endfor
                                    </div>
                                    <span class="rating-text">{{ number_format($averageRating, 1) }} ({{ $reviewsCount }} {{ $reviewsCount == 1 ? 'отзыв' : ($reviewsCount < 5 ? 'отзыва' : 'отзывов') }})</span>
                                </div>
                            @endif
                            
                            <div class="cart-item-price">
                                @if($item->game->isOnDiscount())
                                    <span class="cart-item-old-price">{{ number_format($item->game->price, 0, ',', ' ') }} ₽</span>
                                    <span class="cart-item-current-price">{{ number_format($item->game->discount_price, 0, ',', ' ') }} ₽</span>
                                @else
                                    <span class="cart-item-current-price">{{ number_format($item->game->price, 0, ',', ' ') }} ₽</span>
                                @endif
                            </div>
                            <div class="cart-item-actions">
                                <button type="button" 
                                        class="btn-remove-from-cart" 
                                        data-cart-id="{{ $item->id }}"
                                        onclick="removeFromCart(this)">
                                    <i class="fas fa-trash"></i>
                                    Убрать
                                </button>
                                @php
                                    $inWishlist = auth()->user()->wishlistItems()->where('game_id', $item->game->id)->exists();
                                @endphp
                                <button type="button" 
                                        class="btn-add-to-wishlist {{ $inWishlist ? 'in-wishlist' : '' }}" 
                                        data-game-id="{{ $item->game->id }}"
                                        onclick="toggleWishlist(this)">
                                    @if($inWishlist)
                                        <i class="fas fa-heart"></i>
                                        В желаемом
                                    @else
                                        <i class="far fa-heart"></i>
                                        В желаемое
                                    @endif
                                </button>
                            </div>
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
                    <span class="cart-summary-value">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                </div>
                <a href="" class="btn-checkout">Оплатить</a>
            </div>
        </div>
    @endif
</div>

<script>
function removeFromCart(button) {
    const cartId = button.getAttribute('data-cart-id');
    const cartItem = document.getElementById(`cart-item-${cartId}`);
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Удаляем...';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/cart/remove/${cartId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            cartItem.remove();
            updateCartCounter();
            
            const cartItems = document.querySelector('.cart-items');
            if (cartItems && cartItems.children.length === 0) {
                location.reload();
            }
        } else {
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-trash"></i> Убрать';
    });
}

function toggleWishlist(button) {
    const gameId = button.getAttribute('data-game-id');
    const isInWishlist = button.classList.contains('in-wishlist');
    
    button.disabled = true;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    if (isInWishlist) {
        fetch(`/wishlist/remove-by-game/${gameId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                button.classList.remove('in-wishlist');
                button.innerHTML = '<i class="far fa-heart"></i> В желаемое';
                updateWishlistCounter();
            } else {
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            button.disabled = false;
        });
    } else {
        fetch(`/wishlist/add/${gameId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                button.classList.add('in-wishlist');
                button.innerHTML = '<i class="fas fa-heart"></i> В желаемом';
                updateWishlistCounter();
            } else {
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            button.disabled = false;
        });
    }
}

function updateCartCounter() {
    const cartCounter = document.querySelector('.header-icon-badge');
    if (cartCounter) {
        let currentCount = parseInt(cartCounter.textContent) || 0;
        currentCount = Math.max(0, currentCount - 1);
        cartCounter.textContent = currentCount;
        cartCounter.style.display = currentCount > 0 ? 'block' : 'none';
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
</script>
@endsection