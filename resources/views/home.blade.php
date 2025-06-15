@extends('layouts.app')

@section('content')
<style>
.game-card-wishlist-btn {
    background: white;
    border: 2px solid #393A43;
    color: #393A43;
    padding: 0.5rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

.game-card-wishlist-btn:hover {
    background: #393A43;
    color: white;
}

.game-card-wishlist-btn.in-wishlist {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
}

.game-card-cart-btn.in-cart {
    background: #28a745;
    border-color: #28a745;
}

.game-card-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.game-card-cart-btn {
    flex: 1;
    background: linear-gradient(135deg, #393A43, #2c2d35);
    color: white;
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.game-card-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(57, 58, 67, 0.3);
}

.game-card-image {
    position: relative;
    height: 150px;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    background-color: #f0f0f0;
}

.game-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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

.game-card-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

.game-card-stars {
    display: flex;
    gap: 1px;
}

.game-card-star {
    color: #ddd;
    font-size: 0.875rem;
}

.game-card-star.active {
    color: #ffc107;
}

.game-card-rating-text {
    font-size: 0.75rem;
    color: #666;
}
</style>

<div class="home-container">
    <aside class="home-sidebar">
        <div class="sidebar-section">
            <h3 class="sidebar-title">Популярные категории</h3>
            <ul class="category-list">
                <li class="category-item active" data-filter="all">Все игры</li>
                @foreach($genres as $genre)
                    <li class="category-item" data-filter="genre-{{ $genre->id }}">{{ $genre->name }}</li>
                @endforeach
            </ul>
        </div>
        
        <div class="sidebar-section">
            <h3 class="sidebar-title">Рекомендуемые</h3>
            <ul class="category-list">
                <li class="category-item" data-filter="featured">Популярные</li>
                <li class="category-item" data-filter="new">Новинки</li>
                <li class="category-item" data-filter="sale">Скидки</li>
            </ul>
        </div>
    </aside>
    
    <div class="home-main">
        <div class="search-banner-section">
            <div class="home-search-bar">
                <form action="{{ route('games.search') }}" method="GET">
                    <input type="text" name="query" class="home-search-input" placeholder="Поиск игр..." value="{{ request('query') }}">
                    <button type="submit" class="home-search-button">Поиск</button>
                </form>
            </div>
            
            <div class="home-banner" style="
                @if($banner && $banner->background_image)
                    background-image: url('{{ asset('images/banners/' . $banner->background_image) }}');
                    background-size: cover;
                    background-position: center;
                @else
                    background: {{ $banner->background_color ?? 'linear-gradient(135deg, #393A43 0%, #2c2d35 100%)' }};
                @endif
            ">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.banner.edit') }}" class="banner-edit-btn">Изменить баннер</a>
                    @endif
                @endauth
            </div>
        </div>
        
        <div class="games-grid" id="games-grid">
            @foreach($allGames as $game)
                <div class="game-card" 
                     data-categories="all @if($game->is_featured) featured @endif @if($game->isOnDiscount()) sale @endif @if($game->is_new) new @endif @foreach($game->genres as $genre) genre-{{ $genre->id }} @endforeach">
                    <div class="game-card-image">
                        @php
                            $imagePath = null;
                            if ($game->primaryImage) {
                                $imagePath = $game->primaryImage->image_path;
                            }
                        @endphp
                        
                        @if($imagePath)
                            <img src="{{ asset('images/games/' . $imagePath) }}" alt="{{ $game->title }}" onerror="this.src='/placeholder.svg?height=150&width=200'">
                        @else
                            <div class="placeholder-img">{{ substr($game->title, 0, 1) }}</div>
                        @endif
                        
                        @if($game->isOnDiscount())
                            <div class="game-card-discount">-{{ $game->getDiscountPercentage() }}%</div>
                        @endif
                    </div>
                    <div class="game-card-content">
                        <h3 class="game-card-title">
                            <a href="{{ route('games.show', $game) }}">{{ $game->title }}</a>
                        </h3>
                        <div class="game-card-genres">
                            @foreach($game->genres->take(2) as $genre)
                                <span class="game-card-genre">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        
                        @if($game->average_rating)
                            <div class="game-card-rating">
                                <div class="game-card-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="game-card-star {{ $i <= round($game->average_rating) ? 'active' : '' }}">★</span>
                                    @endfor
                                </div>
                                <span class="game-card-rating-text">{{ $game->average_rating }} ({{ $game->reviews_count }})</span>
                            </div>
                        @endif
                        
                        <div class="game-card-price">
                            @if($game->isOnDiscount())
                                <span class="game-card-old-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                                <span class="game-card-current-price">{{ number_format($game->discount_price, 0, ',', ' ') }} ₽</span>
                            @else
                                <span class="game-card-current-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                            @endif
                        </div>
                        <div class="game-card-actions">
                            @auth
                                @php
                                    $inCart = in_array($game->id, $userCartItems ?? []);
                                    $inWishlist = in_array($game->id, $userWishlistItems ?? []);
                                @endphp
                                <button type="button" 
                                        class="btn-add-to-wishlist {{ $inCart ? 'in-cart' : '' }}" 
                                        data-game-id="{{ $game->id }}"
                                        onclick="toggleCart(this)">
                                    <span class="cart-text" style="font-size: 10px">{{ $inCart ? 'В корзине' : 'В корзину' }}</span>
                                </button>
                                <button type="button" 
                                        class="btn-add-to-wishlist {{ $inWishlist ? 'in-wishlist' : '' }}" 
                                        data-game-id="{{ $game->id }}"
                                        onclick="toggleWishlist(this)"
                                        title="{{ $inWishlist ? 'Убрать из желаемого' : 'Добавить в желаемое' }}">
                                    <span class="cart-text" style="font-size: 10px">{{ $inCart ? 'В желаемом' : 'В желаемое' }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="game-card-cart-btn">Войти</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryItems = document.querySelectorAll('.category-item');
    const gameCards = document.querySelectorAll('.game-card');
    
    gameCards.forEach(card => {
        card.style.display = 'block';
    });
    
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            categoryItems.forEach(cat => cat.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            gameCards.forEach(card => {
                const categories = card.getAttribute('data-categories');
                if (filter === 'all' || (categories && categories.includes(filter))) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

function toggleCart(button) {
    const gameId = button.getAttribute('data-game-id');
    const isInCart = button.classList.contains('in-cart');
    const cartText = button.querySelector('.cart-text');
    
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
                cartText.textContent = 'В корзину';
            } else {
                button.classList.add('in-cart');
                cartText.textContent = 'В корзине';
            }
            updateCartCounter();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function toggleWishlist(button) {
    const gameId = button.getAttribute('data-game-id');
    const isInWishlist = button.classList.contains('in-wishlist');
    const icon = button.querySelector('i');
    
    const url = isInWishlist ? `/wishlist/remove-by-game/${gameId}` : `/wishlist/add/${gameId}`;
    const method = isInWishlist ? 'DELETE' : 'POST';
    
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
            if (isInWishlist) {
                button.classList.remove('in-wishlist');
                icon.className = 'far fa-heart';
                button.title = 'Добавить в желаемое';
            } else {
                button.classList.add('in-wishlist');
                icon.className = 'fas fa-heart';
                button.title = 'Убрать из желаемого';
            }
            updateWishlistCounter();
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
