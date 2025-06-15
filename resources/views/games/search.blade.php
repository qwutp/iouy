@extends('layouts.app')

@section('content')
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
                <p class="search-empty-message">Используйте поиск для поиска игр</p>
                <p class="search-empty-suggestion">Введите название игры в строку поиска выше</p>
            </div>
        @elseif($games->count() == 0)
            <div class="search-empty">
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
                                        class="btn-add-to-wishlist {{ in_array($game->id, $userCartItems ?? []) ? 'in-cart' : '' }}" 
                                        onclick="handleCart({{ $game->id }}, this)"
                                    >
                                        {{ in_array($game->id, $userCartItems ?? []) ? 'В корзине' : 'В корзину' }}
                                    </button>
                                    <button 
                                        class="btn-add-to-wishlist {{ in_array($game->id, $userWishlistItems ?? []) ? 'in-wishlist' : '' }}" 
                                        onclick="handleWishlist({{ $game->id }}, this)"
                                    >
                                        {{ in_array($game->id, $userWishlistItems ?? []) ? 'В желаемом' : 'В желаемое' }}
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
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = 'toast' + (isError ? ' error' : '');
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 100);
    
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
            } else {
                button.classList.add('in-cart');
                button.textContent = 'В корзине';
            }
        } else {
            button.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
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
                button.textContent = 'В желаемое';
            } else {
                button.classList.add('in-wishlist');
                button.textContent = 'В желаемом'
            }
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
</script>
@endauth
@endsection
