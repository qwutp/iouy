<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Silly Games') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="main-layout">
        <header class="header">
            <div class="header-container">
                <div class="header-logo-section">
                    <div class="header-mascot">
                        <img src="/images/logo.jpg" alt="Mascot">
                    </div>
                    <a href="{{ route('home') }}" class="header-logo">silly games</a>
                </div>
                
                <nav class="header-nav">
                    <a href="{{ route('home') }}" class="header-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">Магазин</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="header-nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">Админ-панель</a>
                        @endif
                    @endauth
                </nav>
                
                <div class="header-user">
                    @auth
                        <a href="{{ route('cart.index') }}" class="header-icon">
                            <span>🛒</span>
                            @if(isset($cartCount) && $cartCount > 0)
                                <span class="header-icon-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="header-icon">
                            <span>♡</span>
                            @if(isset($wishlistCount) && $wishlistCount > 0)
                                <span class="header-icon-badge">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('profile.show') }}" class="header-user-info">
                            <div class="header-user-avatar">
                                @if(auth()->user()->avatar)
                                    <img src="/images/avatars/{{ auth()->user()->avatar }}" alt="Avatar">
                                @else
                                    <img src="/images/default-avatar.png" alt="User">
                                @endif
                            </div>
                            <span class="header-user-name">{{ auth()->user()->name }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="header-nav-item">Войти</a>
                        <a href="{{ route('register') }}" class="header-nav-item">Регистрация</a>
                    @endauth
                </div>
            </div>
        </header>
        
        @if(!request()->routeIs(['login', 'register', 'password.*', 'profile.*', 'cart.*', 'wishlist.*', 'admin.*']))
            @if(request()->routeIs('home'))
                <main class="main-content">
                    @yield('content')
                </main>
            @else
                <div class="container">
                    <div class="search-bar">
                        <form action="{{ route('games.search') }}" method="GET">
                            <input type="text" name="query" class="search-input" placeholder="Поиск игр..." value="{{ request('query') }}">
                            <button type="submit" class="search-button">Поиск</button>
                        </form>
                    </div>
                </div>
                <main class="main-content">
                    <div class="container">
                        @yield('content')
                    </div>
                </main>
            @endif
        @else
            <main class="main-content">
                @yield('content')
            </main>
        @endif
        
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h4>Компания</h4>
                        <ul class="footer-links">
                            <li><a href="#">О нас</a></li>
                            <li><a href="#">Карьера</a></li>
                            <li><a href="#">Пресс-центр</a></li>
                            <li><a href="#">Инвесторы</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Поддержка</h4>
                        <ul class="footer-links">
                            <li><a href="#">Помощь</a></li>
                            <li><a href="#">Контакты</a></li>
                            <li><a href="#">Сообщить об ошибке</a></li>
                            <li><a href="#">Статус сервиса</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Сообщество</h4>
                        <ul class="footer-links">
                            <li><a href="#">Блог</a></li>
                            <li><a href="#">Discord</a></li>
                            <li><a href="#">Reddit</a></li>
                            <li><a href="#">Twitter</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Разработчикам</h4>
                        <ul class="footer-links">
                            <li><a href="#">API</a></li>
                            <li><a href="#">Документация</a></li>
                            <li><a href="#">SDK</a></li>
                            <li><a href="#">Партнерство</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>© {{ date('Y') }} Silly Games. Все права защищены.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
