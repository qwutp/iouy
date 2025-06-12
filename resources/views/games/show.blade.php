@extends('layouts.app')

@section('content')
    <div class="game-detail">
        <div class="game-detail-header">
            <game-gallery 
                :images="{{ json_encode($game->images) }}" 
                game-title="{{ $game->title }}"
            ></game-gallery>
            
            <div class="game-info">
                <h1 class="game-info-title">{{ $game->title }}</h1>
                
                <div class="game-info-genres">
                    @foreach($game->genres as $genre)
                        <span class="game-info-genre">{{ $genre->name }}</span>
                    @endforeach
                </div>
                
                <div class="game-info-price">
                    @if($game->isOnDiscount())
                        <span class="game-info-old-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                        <span class="game-info-current-price">{{ number_format($game->discount_price, 0, ',', ' ') }} ₽</span>
                        <span class="game-info-discount">-{{ $game->getDiscountPercentage() }}%</span>
                    @else
                        <span class="game-info-current-price">{{ number_format($game->price, 0, ',', ' ') }} ₽</span>
                    @endif
                </div>
                
                <div class="game-info-actions">
                    <form action="{{ route('cart.add', $game) }}" method="POST">
                        @csrf
                        <button type="submit" class="game-info-cart-btn">
                            <i class="fas fa-shopping-cart"></i>
                            <span>В корзину</span>
                        </button>
                    </form>
                    
                    <form action="{{ route('wishlist.add', $game) }}" method="POST">
                        @csrf
                        <button type="submit" class="game-info-wishlist-btn">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="game-description">
            <h2 class="game-description-title">Описание</h2>
            <div class="game-description-content">
                {!! nl2br(e($game->description)) !!}
            </div>
        </div>
        
        <div class="game-requirements">
            <h2 class="game-requirements-title">Системные требования</h2>
            <div class="game-requirements-grid">
                <div class="game-requirements-section">
                    <h3 class="game-requirements-section-title">Минимальные</h3>
                    <div class="game-requirements-content">
                        {!! nl2br(e($game->system_requirements)) !!}
                    </div>
                </div>
                
                <div class="game-requirements-section">
                    <h3 class="game-requirements-section-title">Рекомендуемые</h3>
                    <div class="game-requirements-content">
                        {!! nl2br(e($game->recommended_requirements)) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="review-section">
            @auth
                <review-form 
                    :game-id="{{ $game->id }}" 
                    @message="showMessage"
                    @review-submitted="$refs.reviewList.loadReviews()"
                ></review-form>
            @else
                <div class="review-login-prompt">
                    <p>Чтобы оставить отзыв, пожалуйста, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a>.</p>
                </div>
            @endauth
            
            <review-list 
                ref="reviewList"
                :game-id="{{ $game->id }}" 
                :initial-reviews="{{ json_encode($game->reviews->load('user', 'likes')) }}"
                :user-id="{{ auth()->id() ?? 'null' }}"
                :is-admin="{{ auth()->check() && auth()->user()->isAdmin() ? 'true' : 'false' }}"
                @message="showMessage"
            ></review-list>
        </div>
    </div>
@endsection
