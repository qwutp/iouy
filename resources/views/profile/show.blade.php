@extends('layouts.app')

@section('content')
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar-container">
                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="/images/avatars/{{ $user->avatar }}" alt="Avatar">
                    @else
                        <img src="/images/default-avatar.png" alt="User Avatar">
                    @endif
                </div>
                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                    @csrf
                    <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;" onchange="this.form.submit()">
                    <label for="avatar" class="profile-avatar-edit" title="Изменить фото">✎</label>
                </form>
            </div>
            
            <div class="profile-info">
                <h2 class="profile-username">{{ $user->name }}</h2>
                <p class="profile-email">{{ $user->email }}</p>
                
                <div class="profile-actions">
                    <a href="{{ route('cart.index') }}" class="profile-btn">Моя корзина</a>
                    <a href="{{ route('wishlist.index') }}" class="profile-btn">Список желаемого</a>
                    <a href="{{ route('profile.settings') }}" class="profile-btn profile-btn-primary">Редактировать профиль</a>
                    <a href="" class="profile-btn">История покупок</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="profile-btn profile-btn-danger">Выйти</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-section">
                <h3 class="profile-section-title">История покупок</h3>
                @if($user->purchases->isEmpty())
                    <div class="empty-state">
                        <h3>У вас пока нет покупок</h3>
                        <p>Ваши покупки будут отображаться здесь</p>
                        <a href="{{ route('home') }}" class="empty-link">Перейти в магазин</a>
                    </div>
                @else
                    <div class="profile-games">
                        @foreach($user->purchases as $purchase)
                            @foreach($purchase->items as $item)
                                <div class="profile-game">
                                    <div class="profile-game-image">
                                        @if($item->game->primaryImage)
                                            <img src="{{ asset('storage/' . $item->game->primaryImage->image_path) }}" alt="{{ $item->game->title }}">
                                        @else
                                            <img src="/placeholder.svg?height=120&width=200" alt="{{ $item->game->title }}">
                                        @endif
                                    </div>
                                    <div class="profile-game-info">
                                        <h4 class="profile-game-title">{{ $item->game->title }}</h4>
                                        <div class="profile-game-meta">
                                            <span>{{ $purchase->created_at->format('d.m.Y') }}</span>
                                            <span>{{ number_format($item->price, 0, ',', ' ') }} ₽</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="profile-section">
                <h3 class="profile-section-title">Мои отзывы</h3>
                @if($user->reviews->isEmpty())
                    <div class="empty-state">
                        <h3>Вы еще не оставили ни одного отзыва</h3>
                        <p>Ваши отзывы будут отображаться здесь</p>
                        <a href="{{ route('home') }}" class="empty-link">Перейти в магазин</a>
                    </div>
                @else
                    <div class="profile-reviews">
                        @foreach($user->reviews as $review)
                            <div class="profile-review">
                                <div class="profile-review-header">
                                    <a href="{{ route('games.show', $review->game) }}" class="profile-review-game">{{ $review->game->title }}</a>
                                    <div class="profile-review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $review->rating ? 'active' : '' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                                <div class="profile-review-content">{{ $review->content }}</div>
                                <div class="profile-review-actions">
                                    <a href="{{ route('games.show', $review->game) }}" class="profile-review-btn">Перейти к игре</a>
                                    <button class="profile-review-btn" onclick="editReview({{ $review->id }}, '{{ $review->content }}', {{ $review->rating }})">Редактировать</button>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="profile-review-btn" onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?')">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="edit-review-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px;">
            <h3 style="margin-bottom: 15px;">Редактировать отзыв</h3>
            <form id="edit-review-form" method="POST">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Оценка:</label>
                    <div id="edit-rating" style="font-size: 24px;">
                        <span class="rating-star" data-value="1">★</span>
                        <span class="rating-star" data-value="2">★</span>
                        <span class="rating-star" data-value="3">★</span>
                        <span class="rating-star" data-value="4">★</span>
                        <span class="rating-star" data-value="5">★</span>
                    </div>
                    <input type="hidden" name="rating" id="edit-rating-input">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Отзыв:</label>
                    <textarea name="content" id="edit-content" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; min-height: 100px;"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeEditModal()" style="padding: 8px 16px; background-color: #e0e0e0; border: none; border-radius: 4px; cursor: pointer;">Отмена</button>
                    <button type="submit" style="padding: 8px 16px; background-color: #9193A6; color: white; border: none; border-radius: 4px; cursor: pointer;">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editReview(id, content, rating) {
            document.getElementById('edit-content').value = content;
            document.getElementById('edit-rating-input').value = rating;
            
            const stars = document.querySelectorAll('#edit-rating .rating-star');
            stars.forEach(star => {
                const value = parseInt(star.getAttribute('data-value'));
                if (value <= rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#e0e0e0';
                }
                
                star.addEventListener('click', function() {
                    const clickedValue = parseInt(this.getAttribute('data-value'));
                    document.getElementById('edit-rating-input').value = clickedValue;
                    
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        if (starValue <= clickedValue) {
                            s.style.color = '#ffc107';
                        } else {
                            s.style.color = '#e0e0e0';
                        }
                    });
                });
            });
            
            document.getElementById('edit-review-form').action = `/reviews/${id}`;
            
            document.getElementById('edit-review-modal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('edit-review-modal').style.display = 'none';
        }
    </script>
@endsection
