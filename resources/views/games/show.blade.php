@extends('layouts.app')

@section('content')

<div class="game-detail">
    <div class="game-detail-header">
        <div class="game-gallery">
            @if($game->images->count() > 0)
                <img id="main-game-image" src="{{ asset('images/games/' . $game->images->first()->image_path) }}" alt="{{ $game->title }}" onerror="this.src='/placeholder.svg?height=400&width=600'">
                
                @if($game->images->count() > 1)
                    <div class="game-images-gallery">
                        @foreach($game->images as $image)
                            <img 
                                src="{{ asset('images/games/' . $image->image_path) }}" 
                                alt="{{ $game->title }}" 
                                onclick="document.getElementById('main-game-image').src = '{{ asset('images/games/' . $image->image_path) }}'"
                                onerror="this.src='/placeholder.svg?height=100&width=150'"
                            >
                        @endforeach
                    </div>
                @endif
            @else
                <img src="/placeholder.svg?height=400&width=600" alt="{{ $game->title }}">
            @endif
        </div>
        
        <div class="game-info">
            <h1 class="game-info-title">{{ $game->title }}</h1>
            
            <div class="game-info-genres">
                @foreach($game->genres as $genre)
                    <span class="game-info-genre">{{ $genre->name }}</span>
                @endforeach
            </div>
            
            @if($game->average_rating)
                <div class="game-info-rating">
                    <div class="game-info-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="game-info-star {{ $i <= round($game->average_rating) ? 'active' : '' }}">★</span>
                        @endfor
                    </div>
                    <span class="game-info-rating-text">{{ $game->average_rating }} из 5 ({{ $game->reviews_count }} {{ $game->reviews_count == 1 ? 'отзыв' : ($game->reviews_count < 5 ? 'отзыва' : 'отзывов') }})</span>
                </div>
            @endif
            
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
                @auth
                    @php
                        $inCart = auth()->user()->cartItems()->where('game_id', $game->id)->exists();
                        $inWishlist = auth()->user()->wishlistItems()->where('game_id', $game->id)->exists();
                    @endphp
                    
                    <button type="button" 
                    style="height: 50px; font-size: 17px"
                            class="btn-add-to-wishlist {{ $inCart ? 'in-cart' : '' }}" 
                            data-game-id="{{ $game->id }}"
                            onclick="toggleGameCart(this)">
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ $inCart ? 'В корзине' : 'В корзину' }}</span>
                    </button>
                    
                    <button type="button" 
                    style="height: 50px; font-size: 17px"
                            class="btn-add-to-wishlist {{ $inWishlist ? 'in-wishlist' : '' }}" 
                            data-game-id="{{ $game->id }}"
                            onclick="toggleGameWishlist(this)">
                        <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart"></i>
                        <span>{{ $inWishlist ? 'В желаемом' : 'В желаемое' }}</span>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="game-info-cart-btn">Войти для покупки</a>
                @endauth
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
        <h2 class="game-description-title">Отзывы</h2>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @auth
            @php
                $userReview = $game->reviews->where('user_id', auth()->id())->first();
            @endphp
            
            @if($userReview)
                <div class="review-item">
                    <div class="review-header">
                        <img src="{{ auth()->user()->avatar ? asset('images/avatars/' . auth()->user()->avatar) : '/placeholder.svg?height=50&width=50' }}" alt="{{ auth()->user()->name }}" class="review-avatar" onerror="this.src='/placeholder.svg?height=50&width=50'">
                        <div>
                            <div class="review-user">{{ auth()->user()->name }}</div>
                            <div class="review-date">{{ $userReview->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $userReview->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <div class="review-content" id="review-content-{{ $userReview->id }}">
                        {{ $userReview->content }}
                    </div>
                    <div class="review-actions">
                        <button class="review-btn review-btn-edit" onclick="toggleEditForm({{ $userReview->id }})">Редактировать</button>
                        <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот отзыв?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="review-btn review-btn-delete">Удалить</button>
                        </form>
                    </div>
                    
                    <div id="edit-form-{{ $userReview->id }}" class="review-edit-form" style="display: none;">
                        <form action="{{ route('reviews.update', $userReview) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="review-form-rating" style="margin-bottom: 1rem;">
                                <label>Оценка:</label>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span 
                                            class="rating-star @if($i <= $userReview->rating) active @endif" 
                                            onclick="setRating(this, {{ $i }}, 'edit-rating-{{ $userReview->id }}')"
                                        >★</span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="edit-rating-{{ $userReview->id }}" value="{{ $userReview->rating }}">
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <label for="edit-content-{{ $userReview->id }}">Отзыв:</label>
                                <textarea 
                                    id="edit-content-{{ $userReview->id }}" 
                                    name="content" 
                                    rows="4" 
                                    style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;"
                                    required
                                >{{ $userReview->content }}</textarea>
                            </div>
                            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <button type="button" class="review-btn" onclick="toggleEditForm({{ $userReview->id }})">Отмена</button>
                                <button type="submit" class="review-btn review-btn-edit">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <form action="{{ route('reviews.store', $game) }}" method="POST" class="review-item">
                    @csrf
                    <div class="review-form-rating" style="margin-bottom: 1rem;">
                        <label>Оценка:</label>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span 
                                    class="rating-star" 
                                    onclick="setRating(this, {{ $i }}, 'new-review-rating')"
                                >★</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="new-review-rating" value="0">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label for="new-review-content">Отзыв:</label>
                        <textarea 
                            id="new-review-content" 
                            name="content" 
                            rows="4" 
                            style="width: 100%; padding: 0.5rem; border-radius: 4px; border: 1px solid #ddd;"
                            placeholder="Напишите ваш отзыв здесь..."
                            required
                        ></textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="review-btn review-btn-edit">Отправить</button>
                    </div>
                </form>
            @endif
        @else
            <div class="review-login-prompt">
                <p>Чтобы оставить отзыв, пожалуйста, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a>.</p>
            </div>
        @endauth
        
        <div class="review-list">
            @foreach($game->reviews->where('user_id', '!=', auth()->id() ?? 0) as $review)
                <div class="review-item">
                    <div class="review-header">
                        <img src="{{ $review->user->avatar ? asset('images/avatars/' . $review->user->avatar) : '/placeholder.svg?height=50&width=50' }}" alt="{{ $review->user->name }}" class="review-avatar" onerror="this.src='/placeholder.svg?height=50&width=50'">
                        <div>
                            <div class="review-user">{{ $review->user->name }}</div>
                            <div class="review-date">{{ $review->created_at->format('d.m.Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <div class="review-content">
                        {{ $review->content }}
                    </div>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="review-actions">
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот отзыв?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="review-btn review-btn-delete">Удалить</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function toggleEditForm(reviewId) {
    const form = document.getElementById(`edit-form-${reviewId}`);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function setRating(element, rating, inputId) {
    const stars = element.parentElement.querySelectorAll('.rating-star');
    stars.forEach(star => star.classList.remove('active'));
    
    for (let i = 0; i < rating; i++) {
        stars[i].classList.add('active');
    }
    
    document.getElementById(inputId).value = rating;
}

function toggleGameCart(button) {
    const gameId = button.getAttribute('data-game-id');
    const isInCart = button.classList.contains('in-cart');
    const span = button.querySelector('span');
    
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
                span.textContent = 'В корзину';
            } else {
                button.classList.add('in-cart');
                span.textContent = 'В корзине';
            }
            updateCartCounter();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function toggleGameWishlist(button) {
    const gameId = button.getAttribute('data-game-id');
    const isInWishlist = button.classList.contains('in-wishlist');
    const icon = button.querySelector('i');
    const span = button.querySelector('span');
    
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
                span.textContent = 'В желаемое';
            } else {
                button.classList.add('in-wishlist');
                icon.className = 'fas fa-heart';
                span.textContent = 'В желаемом';
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
