@extends('layouts.app')

@section('content')
<style>
    .wishlist-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .wishlist-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .wishlist-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    .wishlist-empty {
        text-align: center;
        padding: 3rem 0;
    }
    
    .wishlist-empty-message {
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
    
    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .wishlist-item {
        background-color: #f8f8f8;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .wishlist-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .wishlist-item-image {
        position: relative;
        height: 150px;
        overflow: hidden;
        background-color: #e0e0e0;
    }
    
    .wishlist-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .wishlist-item-discount {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background-color: #dc3545;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .wishlist-item-content {
        padding: 1rem;
    }
    
    .wishlist-item-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .wishlist-item-title a {
        color: #333;
        text-decoration: none;
    }
    
    .wishlist-item-title a:hover {
        color: #393A43;
    }
    
    .wishlist-item-genres {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .wishlist-item-genre {
        background-color: #e0e0e0;
        padding: 0.125rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        color: #666;
    }
    
    .wishlist-item-price {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .wishlist-item-current-price {
        font-size: 1.125rem;
        font-weight: 600;
        color: #393A43;
    }
    
    .wishlist-item-old-price {
        font-size: 0.875rem;
        text-decoration: line-through;
        color: #888;
        margin-right: 0.5rem;
    }
    
    .wishlist-item-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-add-to-cart {
        flex: 1;
        background: linear-gradient(135deg, #393A43, #2c2d35);
        color: white;
        border: none;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .btn-add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(57, 58, 67, 0.3);
    }
    
    .btn-add-to-cart.in-cart {
        background: #28a745;
    }
    
    .btn-remove-from-wishlist {
        flex: 1;
        background: white;
        border: 1px solid #dc3545;
        color: #dc3545;
        padding: 0.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        gap: 0.25rem;
    }
    
    .btn-remove-from-wishlist:hover {
        background: #dc3545;
        color: white;
    }
    
    .placeholder-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        color: #666;
        font-size: 24px;
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
</style>

<div class="wishlist-container">
    <div class="wishlist-header">
        <h1 class="wishlist-title">Список желаемого</h1>
        <button onclick="history.back()" class="btn-back">
            <i class="fas fa-arrow-left"></i>
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
                                    class="btn-add-to-cart {{ $inCart ? 'in-cart' : '' }}" 
                                    data-game-id="{{ $item->game->id }}"
                                    onclick="toggleCartFromWishlist(this)">
                                {{ $inCart ? 'В корзине' : 'В корзину' }}
                            </button>
                            <button type="button" 
                                    class="btn-remove-from-wishlist" 
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
