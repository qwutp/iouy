@extends('layouts.app')

@section('content')
<style>
.game-detail {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.game-detail-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.game-gallery {
    position: relative;
}

.game-gallery img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.game-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.game-info-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #393A43;
    margin: 0;
}

.game-info-genres {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.game-info-genre {
    background: linear-gradient(135deg, #393A43, #2c2d35);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.game-info-price {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 1.5rem;
    font-weight: bold;
}

.game-info-current-price {
    color: #393A43;
}

.game-info-old-price {
    text-decoration: line-through;
    color: #999;
    font-size: 1.25rem;
}

.game-info-discount {
    background: #e74c3c;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 1rem;
}

.game-info-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.game-info-cart-btn {
    flex: 2;
    background: linear-gradient(135deg, #393A43, #2c2d35);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.game-info-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(57, 58, 67, 0.3);
}

.game-info-cart-btn.in-cart {
    background: #28a745;
}

.game-info-wishlist-btn {
    flex: 1;
    background: white;
    border: 2px solid #393A43;
    color: #393A43;
    padding: 1rem;
    border-radius: 12px;
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.game-info-wishlist-btn:hover {
    background: #393A43;
    color: white;
    transform: translateY(-2px);
}

.game-info-wishlist-btn.in-wishlist {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
}

.game-description {
    margin-bottom: 3rem;
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
}

.game-requirements {
    margin-bottom: 3rem;
}

.game-description-title, .game-requirements-title {
    font-size: 1.75rem;
    font-weight: bold;
    color: #393A43;
    margin-bottom: 1rem;
}

.game-description-content, .game-requirements-content {
    line-height: 1.6;
    color: #666;
}

.game-requirements-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.game-requirements-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
}

.game-requirements-section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #393A43;
    margin-bottom: 1rem;
}

.review-section {
    margin-top: 3rem;
}

.review-login-prompt {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 2rem;
}

.review-login-prompt a {
    color: #393A43;
    text-decoration: none;
    font-weight: 600;
}

.review-login-prompt a:hover {
    text-decoration: underline;
}

.game-images-gallery {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.game-images-gallery img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.game-images-gallery img:hover {
    transform: scale(1.05);
}

.review-item {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.review-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.review-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 1rem;
    object-fit: cover;
}

.review-user {
    font-weight: 600;
    color: #393A43;
}

.review-date {
    color: #777;
    font-size: 0.875rem;
    margin-left: auto;
}

.review-rating {
    color: #ffc107;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.review-content {
    color: #555;
    line-height: 1.6;
}

.review-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
    justify-content: flex-end;
}

.review-btn {
    background: #f0f0f0;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.review-btn:hover {
    background: #e0e0e0;
}

.review-btn-edit {
    color: #2c3e50;
}

.review-btn-delete {
    color: #e74c3c;
}

.review-edit-form {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
    border: 1px solid #e0e0e0;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.game-info-rating {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.game-info-stars {
    display: flex;
    gap: 2px;
}

.game-info-star {
    color: #ddd;
    font-size: 1.25rem;
}

.game-info-star.active {
    color: #ffc107;
}

.game-info-rating-text {
    font-size: 1rem;
    color: #666;
    font-weight: 500;
}
</style>

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
                            class="game-info-cart-btn {{ $inCart ? 'in-cart' : '' }}" 
                            data-game-id="{{ $game->id }}"
                            onclick="toggleGameCart(this)">
                        <i class="fas fa-shopping-cart"></i>
                        <span>{{ $inCart ? 'В корзине' : 'В корзину' }}</span>
                    </button>
                    
                    <button type="button" 
                            class="game-info-wishlist-btn {{ $inWishlist ? 'in-wishlist' : '' }}" 
                            data-game-id="{{ $game->id }}"
                            onclick="toggleGameWishlist(this)">
                        <i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart"></i>
                        <span>{{ $inWishlist ? 'Убрать' : 'В желаемое' }}</span>
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
    // Reset all stars
    const stars = element.parentElement.querySelectorAll('.rating-star');
    stars.forEach(star => star.classList.remove('active'));
    
    // Set active stars
    for (let i = 0; i < rating; i++) {
        stars[i].classList.add('active');
    }
    
    // Set hidden input value
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
                span.textContent = 'Убрать';
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
