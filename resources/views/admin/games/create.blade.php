@extends('layouts.app')

@section('content')
<div class="container">
    <div class="admin-header">
        <div class="admin-header-top">
            <a href="{{ route('admin.games') }}" class="admin-back-btn">← Назад к списку игр</a>
        </div>
        <div class="admin-header-main">
            <h1 class="admin-header-title">Добавление новой игры</h1>
        </div>
        <p class="admin-header-subtitle">Заполните информацию о новой игре</p>
    </div>

    @if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="admin-content">
        <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            
            <div class="admin-form-section">
                <h2 class="admin-form-section-title">Основная информация</h2>
                
                <div class="admin-form-group">
                    <label for="title">Название игры</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                </div>
                
                <div class="admin-form-group">
                    <label for="description">Описание</label>
                    <textarea id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                </div>
                
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label for="price">Цена (₽)</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                    </div>
                    
                    <div class="admin-form-group">
                        <label for="discount_price">Цена со скидкой (₽)</label>
                        <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" min="0" step="0.01">
                    </div>
                </div>
            </div>
            
            <div class="admin-form-section">
                <h2 class="admin-form-section-title">Системные требования</h2>
                
                <div class="admin-form-group">
                    <label for="system_requirements">Минимальные требования</label>
                    <textarea id="system_requirements" name="system_requirements" rows="4" required>{{ old('system_requirements') }}</textarea>
                </div>
                
                <div class="admin-form-group">
                    <label for="recommended_requirements">Рекомендуемые требования</label>
                    <textarea id="recommended_requirements" name="recommended_requirements" rows="4" required>{{ old('recommended_requirements') }}</textarea>
                </div>
            </div>
            
            <div class="admin-form-section">
                <h2 class="admin-form-section-title">Жанры</h2>
                <p class="admin-form-help">Выберите один или несколько жанров для игры</p>
                
                @if(isset($genres) && $genres->count() > 0)
                    <div class="admin-form-genres">
                        @foreach($genres as $genre)
                            <div class="admin-form-genre">
                                <input type="checkbox" id="genre-{{ $genre->id }}" name="genres[]" value="{{ $genre->id }}" 
                                       @if(in_array($genre->id, old('genres', []))) checked @endif>
                                <label for="genre-{{ $genre->id }}">{{ $genre->name }}</label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="admin-empty-genres">
                        <p>Жанры не найдены. Сначала создайте жанры в базе данных.</p>
                        <p><strong>Выполните команду:</strong> <code>php artisan db:seed --class=GenreSeeder</code></p>
                    </div>
                @endif
            </div>
            
            <div class="admin-form-section">
                <h2 class="admin-form-section-title">Изображения</h2>
                
                <div class="admin-form-group">
                    <label for="images">Изображения игры</label>
                    <input type="file" id="images" name="images[]" multiple required accept="image/*" class="admin-file-input">
                    <p class="admin-form-help">Выберите от 1 до 6 изображений для игры (скриншоты, обложка и т.д.). <strong>Максимум: 6 изображений</strong></p>
                </div>
                
                <div class="admin-form-group">
                    <label for="primary_image">Основное изображение</label>
                    <input type="number" id="primary_image" name="primary_image" value="{{ old('primary_image', 1) }}" min="1" required>
                    <p class="admin-form-help">Укажите номер изображения, которое будет основным</p>
                </div>
            </div>
            
            <div class="admin-form-section">
                <h2 class="admin-form-section-title">Дополнительные настройки</h2>
                
                <div class="admin-form-options">
                    <div class="admin-form-option">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_featured">Популярная игра</label>
                        </div>
                    </div>
                    
                    <div class="admin-form-option">
                        <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_new">Новинка</label>
                        </div>
                    </div>
                    
                    <div class="admin-form-option">
                        <input type="checkbox" id="is_on_sale" name="is_on_sale" value="1" {{ old('is_on_sale') ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_on_sale">Распродажа</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">ДОБАВИТЬ ИГРУ</button>
                <a href="{{ route('admin.games') }}" class="admin-form-cancel">ОТМЕНА</a>
            </div>
        </form>
    </div>
</div>
@endsection
