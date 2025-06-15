@extends('layouts.app')

@section('content')
<style>
    .search-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .search-header {
        margin-bottom: 2rem;
    }
    
    .search-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .search-count {
        color: #666;
    }
    
    .search-form {
        margin-bottom: 2rem;
        display: flex;
        gap: 0.5rem;
    }
    
    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
        border-radius: 0.5rem;
        font-size: 1rem;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #393A43;
    }
    
    .search-button {
        background: linear-gradient(135deg, #393A43, #2c2d35);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .search-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(57, 58, 67, 0.3);
    }
    
    .search-empty {
        text-align: center;
        padding: 3rem 0;
    }
    
    .search-empty-message {
        font-size: 1.25rem;
        color: #666;
        margin-bottom: 1.5rem;
    }
    
    .search-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .search-item {
        background-color: #f8f8f8;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .search-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .search-item-image {
        position: relative;
        height: 150px;
        overflow: hidden;
        background-color: #e0e0e0;
    }
    
    .search-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .search-item-discount {
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
    
    .search-item-content {
        padding: 1rem;
    }
    
    .search-item-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .search-item-title a {
        color: #333;
        text-decoration: none;
    }
    
    .search-item-title a:hover {
        color: #393A43;
    }
    
    .search-item-genres {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .search-item-genre {
        background-color: #e0e0e0;
        padding: 0.125rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        color: #666;
    }
    
    .search-item-price {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .search-item-current-price {
        font-size: 1.125rem;
        font-weight: 600;
        color: #393A43;
    }
    
    .search-item-old-price {
        font-size: 0.875rem;
        text-decoration: line-through;
        color: #888;
        margin-right: 0.5rem;
    }
    
    .search-item-actions {
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
    
    .btn-add-to-wishlist {
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
    }
    
    .btn-add-to-wishlist:hover {
        background: #393A43;
        color: white;
    }
    
    .btn-add-to-wishlist.in-wishlist {
        background: #dc3545;
        border-color: #dc3545;
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
</style>

<div class="search-container">
    <div class="search-header">
        <h1 class="search-title">Результаты поиска</h1>
        <p class="search-count">Найдено: {{ $games->count() }} игр</p>
    </div>
    
    <form action="{{ route('games.search') }}" method="GET" class="search-form">
        <input type="text" name="query" class="search-input" placeholder="Поиск игр..." value="{{ request('query') }}">
        <button type="submit" class="search-button">Поиск</button>
    </form>
    
    @if($games->isEmpty())
        <div class="search-empty">
            <p class="search-empty-message">По вашему запросу ничего не найдено</p>
            <a href="{{ route('games.index') }}" class="search-button">Перейти к играм</a>
        </div>
    @else
        <div class="search-grid">
            @foreach($games as $game)
                <div class="search-item">
                    <div class="search-item-image">
                        @php
                            $imagePath = null;
                            if ($game->primaryImage) {
                                $imagePath = $game->primaryImage->image_path;
                            }
                        @endphp
                        
                        @if($imagePath)
                            <img src="{{ asset('images/games/' . $imagePath) }}" alt="{{ $game->title }}" onerror="this.parentElement.innerHTML='<div class=\'placeholder-img\'>{{ substr($game->title, 0, 1) }}</div>'">
                        @else
                            <div class="placeholder-img">{{ substr($game->title, 0, 1) }}</div>
                        @endif
                        
                        @if($game->isOnDiscount())
                            <div class="search-item-discount">-{{ $game->getDiscountPercentage() }}%</div>
                        @endif
                    </div>
                    <div class="search-item-content">
                        <h3 class="search-item-title">
                            <a href="{{ route('games.show', $game) }}">{{ $game->title }}</a>
                        </h3>
                        <div class="search-item-genres">
                            @foreach($game->genres->take(2) as $genre)
                                <span class="search-item-genre">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        <div class="search-item-price">
                            @if($game->isOnDiscount())
                                <span class="search-item-old-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                                <span class="search-item-current-price">{{ number_format($game->discount_price, 0, ',', ' ') }} ₽</span>
                            @else
                                <span class="search-item-current-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                            @endif
                        </div>
                        <div class="search-item-actions">
                            @auth
                                <form action="{{ route('cart.add', $game) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="btn-add-to-cart">В корзину</button>
                                </form>
                                <button 
                                    class="btn-add-to-wishlist {{ in_array($game->id, $userWishlistItems ?? []) ? 'in-wishlist' : '' }}" 
                                    onclick="toggleWishlist(this, {{ $game->id }})"
                                    title="{{ in_array($game->id, $userWishlistItems ?? []) ? 'Удалить из списка желаемого' : 'Добавить в список желаемого' }}"
                                >
                                    <i class="fa{{ in_array($game->id, $userWishlistItems ?? []) ? 's' : 'r' }} fa-heart"></i>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-add-to-cart" style="flex: 1;">Войти</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function toggleWishlist(button, gameId) {
    const isInWishlist = button.classList.contains('in-wishlist');
    const icon = button.querySelector('i');
    
    const url = isInWishlist ? `/wishlist/remove-by-game/${gameId}` : `/wishlist/add/${gameId}`;
    const method = isInWishlist ? 'DELETE' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (isInWishlist) {
                button.classList.remove('in-wishlist');
                icon.className = 'far fa-heart';
                button.title = 'Добавить в список желаемого';
            } else {
                button.classList.add('in-wishlist');
                icon.className = 'fas fa-heart';
                button.title = 'Удалить из списка желаемого';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection
