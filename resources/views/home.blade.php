@extends('layouts.app')

@section('content')
    <div class="home-container">
        <aside class="home-sidebar">
            <div class="sidebar-section">
                <h3 class="sidebar-title">Популярные категории</h3>
                <ul class="category-list">
                    <li class="category-item active" data-filter="all">Все игры</li>
                    <li class="category-item" data-filter="action">Экшен</li>
                    <li class="category-item" data-filter="adventure">Приключения</li>
                    <li class="category-item" data-filter="rpg">RPG</li>
                    <li class="category-item" data-filter="strategy">Стратегии</li>
                    <li class="category-item" data-filter="simulation">Симуляторы</li>
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
                    <div class="banner-content">
                        <h2>{{ $banner->title ?? 'Лучшие игры здесь' }}</h2>
                        <p>{{ $banner->subtitle ?? 'Найдите свою следующую любимую игру' }}</p>
                    </div>
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.banner.edit') }}" class="banner-edit-btn">Изменить баннер</a>
                        @endif
                    @endauth
                </div>
            </div>
            
            <div class="games-grid" id="games-grid">
                @foreach($featuredGames->take(10) as $game)
                    <div class="game-card" data-categories="featured">
                        <div class="game-card-image">
                            @if($game->primaryImage)
                                <img src="{{ asset('images/games/' . $game->primaryImage->image_path) }}" alt="{{ $game->title }}">
                            @else
                                <img src="/placeholder.svg?height=150&width=200" alt="{{ $game->title }}">
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
                                    <form action="{{ route('cart.add', $game) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="game-card-cart-btn">В корзину</button>
                                    </form>
                                    <form action="{{ route('wishlist.add', $game) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="game-card-wishlist-btn">♡</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="game-card-cart-btn">Войти</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @foreach($newGames->take(5) as $game)
                    <div class="game-card" data-categories="new" style="display: none;">
                        <div class="game-card-image">
                            @if($game->primaryImage)
                                <img src="{{ asset('images/games/' . $game->primaryImage->image_path) }}" alt="{{ $game->title }}">
                            @else
                                <img src="/placeholder.svg?height=150&width=200" alt="{{ $game->title }}">
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
                                    <form action="{{ route('cart.add', $game) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="game-card-cart-btn">В корзину</button>
                                    </form>
                                    <form action="{{ route('wishlist.add', $game) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="game-card-wishlist-btn">♡</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="game-card-cart-btn">Войти</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @foreach($saleGames->take(5) as $game)
                    <div class="game-card" data-categories="sale" style="display: none;">
                        <div class="game-card-image">
                            @if($game->primaryImage)
                                <img src="{{ asset('images/games/' . $game->primaryImage->image_path) }}" alt="{{ $game->title }}">
                            @else
                                <img src="/placeholder.svg?height=150&width=200" alt="{{ $game->title }}">
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
                                    <form action="{{ route('cart.add', $game) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="game-card-cart-btn">В корзину</button>
                                    </form>
                                    <form action="{{ route('wishlist.add', $game) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="game-card-wishlist-btn">♡</button>
                                    </form>
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
            
            categoryItems.forEach(item => {
                item.addEventListener('click', function() {
                    categoryItems.forEach(cat => cat.classList.remove('active'));
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    gameCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else {
                            const categories = card.getAttribute('data-categories');
                            if (categories && categories.includes(filter)) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            });
        });
    </script>
@endsection
