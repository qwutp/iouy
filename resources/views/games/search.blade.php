@extends('layouts.app')

@section('content')
<style>
    .search-page {
        min-height: 80vh;
        background-color: #f8f8f8;
        padding: 2rem 0;
    }
    
    .search-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .search-results-header {
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .search-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .search-count {
        font-size: 1.125rem;
        color: #666;
        font-weight: 500;
    }
    
    .search-query {
        color: #393A43;
        font-weight: 600;
    }
    
    .search-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .search-empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .search-empty-message {
        font-size: 1.25rem;
        color: #666;
        margin-bottom: 1rem;
    }
    
    .search-empty-suggestion {
        color: #888;
        font-size: 1rem;
    }
    
    .search-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .game-card {
        background-color: #f8f8f8;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .game-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .game-image {
        position: relative;
        height: 150px;
        overflow: hidden;
        background-color: #e0e0e0;
    }
    
    .game-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .game-discount {
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
    
    .placeholder-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        color: #666;
        font-size: 24px;
        font-weight: 600;
    }
    
    .game-content {
        padding: 1rem;
    }
    
    .game-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .game-title a {
        color: #333;
        text-decoration: none;
    }
    
    .game-title a:hover {
        color: #393A43;
    }
    
    .game-rating {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        gap: 0.5rem;
    }
    
    .rating-stars {
        display: flex;
        gap: 0.125rem;
    }
    
    .rating-star {
        color: #ffd700;
        font-size: 0.875rem;
    }
    
    .rating-star.empty {
        color: #ddd;
    }
    
    .rating-text {
        font-size: 0.875rem;
        color: #666;
    }
    
    .game-genres {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .game-genre {
        background-color: #e0e0e0;
        padding: 0.125rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        color: #666;
    }
    
    .game-price {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        gap: 0.5rem;
    }
    
    .current-price {
        font-size: 1.125rem;
        font-weight: 600;
        color: #393A43;
    }
    
    .old-price {
        font-size: 0.875rem;
        text-decoration: line-through;
        color: #888;
    }
    
    .game-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-cart {
        flex: 1;
        background: linear-gradient(135deg, #393A43, #2c2d35);
        color: white;
        border: none;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(57, 58, 67, 0.3);
    }
    
    .btn-cart:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-cart.in-cart {
        background: #dc3545;
    }
    
    .btn-cart.in-cart:hover {
        background: #c82333;
    }
    
    .btn-wishlist {
        background: white;
        border: 1px solid #393A43;
        color: #393A43;
        padding: 0.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .btn-wishlist:hover {
        background: #393A43;
        color: white;
    }
    
    .btn-wishlist.in-wishlist {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .pagination-wrapper {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .pagination a,
    .pagination span {
        padding: 0.5rem 0.75rem;
        border: 1px solid #ddd;
        color: #666;
        text-decoration: none;
        border-radius: 0.25rem;
        transition: all 0.3s ease;
    }
    
    .pagination a:hover {
        background: #393A43;
        color: white;
        border-color: #393A43;
    }
    
    .pagination .active span {
        background: #393A43;
        color: white;
        border-color: #393A43;
    }
    
    /* Toast notification styles */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        opacity: 0;
        transform: translateX(400px);
        transition: all 0.3s ease;
    }
    
    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }
    
    .toast.error {
        background: #dc3545;
    }
    
    @media (max-width: 768px) {
        .search-title {
            font-size: 1.5rem;
        }
        
        .search-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .search-container {
            padding: 0 0.5rem;
        }
        
        .toast {
            right: 10px;
            left: 10px;
            transform: translateY(-100px);
        }
        
        .toast.show {
            transform: translateY(0);
        }
    }
</style>

<div class="search-page">
    <div class="search-container">
        @if(!empty($query))
            <div class="search-results-header">
                <h1 class="search-title">Результаты поиска</h1>
                <p class="search-count">
                    @if($games->count() > 0)
                        Найдено <strong>{{ $games->total() }}</strong> {{ $games->total() == 1 ? 'игра' : ($games->total() < 5 ? 'игры' : 'игр') }} 
                        по запросу "<span class="search-query">{{ $query }}</span>"
                    @else
                        По запросу "<span class="search-query">{{ $query }}</span>" ничего не найдено
                    @endif
                </p>
            </div>
        @endif
        
        @if(empty($query))
            <div class="search-empty">
                <div class="search-empty-icon">🎮</div>
                <p class="search-empty-message">Используйте поиск для поиска игр</p>
                <p class="search-empty-suggestion">Введите название игры в строку поиска выше</p>
            </div>
        @elseif($games->count() == 0)
            <div class="search-empty">
                <div class="search-empty-icon">😔</div>
                <p class="search-empty-message">По вашему запросу ничего не найдено</p>
                <p class="search-empty-suggestion">Попробуйте изменить поисковый запрос или проверьте правильность написания</p>
            </div>
        @else
            <div class="search-grid">
                @foreach($games as $game)
                    <div class="game-card">
                        <div class="game-image">
                            @php
                                $imagePath = null;
                                if ($game->primaryImage) {
                                    $imagePath = $game->primaryImage->image_path;
                                }
                            @endphp
                            
                            @if($imagePath && file_exists(public_path('images/games/' . $imagePath)))
                                <img src="{{ asset('images/games/' . $imagePath) }}" alt="{{ $game->title }}">
                            @else
                                <div class="placeholder-img">{{ substr($game->title, 0, 1) }}</div>
                            @endif
                            
                            @if($game->isOnDiscount())
                                <div class="game-discount">-{{ $game->getDiscountPercentage() }}%</div>
                            @endif
                        </div>
                        
                        <div class="game-content">
                            <h3 class="game-title">
                                <a href="{{ route('games.show', $game) }}">{{ $game->title }}</a>
                            </h3>
                            
                            @if($game->reviews_count > 0)
                                <div class="game-rating">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="rating-star {{ $i <= round($game->average_rating) ? '' : 'empty' }}">★</span>
                                        @endfor
                                    </div>
                                    <span class="rating-text">
                                        {{ number_format($game->average_rating, 1) }} ({{ $game->reviews_count }})
                                    </span>
                                </div>
                            @endif
                            
                            <div class="game-genres">
                                @foreach($game->genres->take(2) as $genre)
                                    <span class="game-genre">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                            
                            <div class="game-price">
                                @if($game->isOnDiscount())
                                    <span class="old-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                                    <span class="current-price">{{ number_format($game->discount_price, 0, ',', ' ') }} ₽</span>
                                @else
                                    <span class="current-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                                @endif
                            </div>
                            
                            <div class="game-actions">
                                @auth
                                    <button 
                                        class="btn-cart {{ in_array($game->id, $userCartItems ?? []) ? 'in-cart' : '' }}" 
                                        onclick="handleCart({{ $game->id }}, this)"
                                    >
                                        {{ in_array($game->id, $userCartItems ?? []) ? 'Убрать' : 'В корзину' }}
                                    </button>
                                    <button 
                                        class="btn-wishlist {{ in_array($game->id, $userWishlistItems ?? []) ? 'in-wishlist' : '' }}" 
                                        onclick="handleWishlist({{ $game->id }}, this)"
                                    >
                                        {{ in_array($game->id, $userWishlistItems ?? []) ? '❤️' : '🤍' }}
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn-cart">Войти</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($games->hasPages())
                <div class="pagination-wrapper">
                    {{ $games->appends(['query' => $query])->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@auth
<script>
function showToast(message, isError = false) {
    // Remove existing toast
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create new toast
    const toast = document.createElement('div');
    toast.className = 'toast' + (isError ? ' error' : '');
    toast.textContent = message;
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Hide toast
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function handleCart(gameId, button) {
    const isInCart = button.classList.contains('in-cart');
    const originalText = button.textContent;
    
    button.disabled = true;
    button.textContent = isInCart ? 'Убираем...' : 'Добавляем...';
    
    const url = isInCart ? `/cart/remove-by-game/${gameId}` : `/cart/add/${gameId}`;
    const method = isInCart ? 'DELETE' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (isInCart) {
                button.classList.remove('in-cart');
                button.textContent = 'В корзину';
                showToast('Игра удалена из корзины');
            } else {
                button.classList.add('in-cart');
                button.textContent = 'Убрать';
                showToast('Игра добавлена в корзину!');
            }
        } else {
            showToast(data.message || 'Произошла ошибка', true);
            button.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Произошла ошибка', true);
        button.textContent = originalText;
    })
    .finally(() => {
        button.disabled = false;
    });
}

function handleWishlist(gameId, button) {
    const isInWishlist = button.classList.contains('in-wishlist');
    
    button.disabled = true;
    
    const url = isInWishlist ? `/wishlist/remove-by-game/${gameId}` : `/wishlist/add/${gameId}`;
    const method = isInWishlist ? 'DELETE' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (isInWishlist) {
                button.classList.remove('in-wishlist');
                button.textContent = '🤍';
                showToast('Игра удалена из списка желаемого');
            } else {
                button.classList.add('in-wishlist');
                button.textContent = '❤️';
                showToast('Игра добавлена в список желаемого!');
            }
        } else {
            showToast(data.message || 'Произошла ошибка', true);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Произошла ошибка', true);
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
@endauth
@endsection
