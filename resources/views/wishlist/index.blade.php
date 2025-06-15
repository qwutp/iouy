@extends('layouts.app')

@section('content')

<div class="wishlist-container">
    <div class="wishlist-header">
        <h1 class="wishlist-title">Список желаемого</h1>
        <button onclick="history.back()" class="btn-back">
            Назад
        </button>
    </div>
    
    @if($wishlistItems->isEmpty())
        <div class="wishlist-empty">
            <p class="wishlist-empty-message">Ваш список желаемого пуст</p>
            <a href="{{ route('games.index') }}" class="btn-continue-shopping">Перейти к играм</a>
        </div>
    @else
        <div class="wishlist-grid">
            @foreach($wishlistItems as $item)
                <div class="wishlist-item" id="wishlist-item-{{ $item->id }}">
                    <div class="wishlist-item-image">
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
                            <div class="wishlist-item-discount">-{{ $item->game->getDiscountPercentage() }}%</div>
                        @endif
                    </div>
                    <div class="wishlist-item-content">
                        <h3 class="wishlist-item-title">
                            <a href="{{ route('games.show', $item->game) }}">{{ $item->game->title }}</a>
                        </h3>
                        <div class="wishlist-item-genres">
                            @foreach($item->game->genres->take(2) as $genre)
                                <span class="wishlist-item-genre">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        <div class="wishlist-item-price">
                            @if($item->game->isOnDiscount())
                                <span class="wishlist-item-old-price">{{ number_format($item->game->price, 0, ',', ' ') }} ₽</span>
                                <span class="wishlist-item-current-price">{{ number_format($item->game->discount_price, 0, ',', ' ') }} ₽</span>
                            @else
                                <span class="wishlist-item-current-price">{{ number_format($item->game->price, 0, ',', ' ') }} ₽</span>
                            @endif
                        </div>
                        <div class="wishlist-item-actions">
                            @php
                                $inCart = auth()->user()->cartItems()->where('game_id', $item->game->id)->exists();
                            @endphp
                            <button type="button" 
                                    class="btn-add-to-wishlist {{ $inCart ? 'in-cart' : '' }}" 
                                    data-game-id="{{ $item->game->id }}"
                                    onclick="toggleCartFromWishlist(this)">
                                {{ $inCart ? 'В корзине' : 'В корзину' }}
                            </button>
                            <button type="button" 
                                    class="btn-remove-from-cart" 
                                    data-wishlist-id="{{ $item->id }}"
                                    data-game-id="{{ $item->game->id }}"
                                    onclick="removeFromWishlist(this)">
                                <i class="fas fa-heart-broken"></i>
                                Убрать
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function toggleCartFromWishlist(button) {
    const gameId = button.getAttribute('data-game-id');
    const isInCart = button.classList.contains('in-cart');
    
    const url = isInCart ? `/cart/remove-by-game/${gameId}` : `/cart/add/${gameId}`;
    const method = isInCart ? 'DELETE' : 'POST';
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (isInCart) {
                button.classList.remove('in-cart');
                button.textContent = 'В корзину';
            } else {
                button.classList.add('in-cart');
                button.textContent = 'В корзине';
            }
            updateCartCounter();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function removeFromWishlist(button) {
    const wishlistId = button.getAttribute('data-wishlist-id');
    const gameId = button.getAttribute('data-game-id');
    const wishlistItem = document.getElementById(`wishlist-item-${wishlistId}`);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/wishlist/remove/${wishlistId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            wishlistItem.remove();
            updateWishlistCounter();
            
            const wishlistGrid = document.querySelector('.wishlist-grid');
            if (wishlistGrid && wishlistGrid.children.length === 0) {
                const wishlistContainer = document.querySelector('.wishlist-container');
                wishlistContainer.innerHTML = `
                    <div class="wishlist-header">
                        <h1 class="wishlist-title">Список желаемого</h1>
                        <button onclick="history.back()" class="btn-back">
                            <i class="fas fa-arrow-left"></i>
                            Назад
                        </button>
                    </div>
                    <div class="wishlist-empty">
                        <p class="wishlist-empty-message">Ваш список желаемого пуст</p>
                        <a href="{{ route('games.index') }}" class="btn-continue-shopping">Перейти к играм</a>
                    </div>
                `;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateCartCounter() {
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        const cartCounter = document.querySelector('.cart-counter');
        if (cartCounter) {
            cartCounter.textContent = data.count;
            cartCounter.style.display = data.count > 0 ? 'block' : 'none';
        }
    })
    .catch(error => {
        console.error('Error updating cart counter:', error);
    });
}

function updateWishlistCounter() {
    fetch('/wishlist/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        const wishlistCounter = document.querySelector('.wishlist-counter');
        if (wishlistCounter) {
            wishlistCounter.textContent = data.count;
            wishlistCounter.style.display = data.count > 0 ? 'block' : 'none';
        }
    })
    .catch(error => {
        console.error('Error updating wishlist counter:', error);
    });
}
</script>
@endsection
