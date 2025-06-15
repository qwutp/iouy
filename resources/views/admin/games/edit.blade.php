@extends('layouts.app')

@section('content')
  <div class="container">
     <div class="admin-header">
        <div class="admin-header-top">
            <a href="{{ route('admin.games') }}" class="admin-back-btn">← Назад к списку игр</a>
        </div>
        <div class="admin-header-main">
            <h1 class="admin-header-title">Редактирование игры</h1>
        </div>
        <p class="admin-header-subtitle">Измените данные об игре</p>
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
        <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data" class="admin-form">
            @csrf
            @method('PUT')
            
<div class="admin-form-section">
    <h2 class="admin-form-section-title">Основная информация</h2>

            <div class="admin-form-group">
                <label for="title">Название</label>
                <input type="text" id="title" name="title" value="{{ old('title', $game->title) }}" required>
                @error('title')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="admin-form-group">
                <label for="description">Описание</label>
                <textarea id="description" name="description" rows="5" required>{{ old('description', $game->description) }}</textarea>
                @error('description')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="admin-form-group">
                <label for="price">Цена (₽)</label>
                <input type="number" id="price" name="price" value="{{ old('price', $game->price) }}" min="0" step="0.01" required>
                @error('price')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="admin-form-group">
                <label for="discount_price">Цена со скидкой (₽)</label>
                <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', $game->discount_price) }}" min="0" step="0.01">
                @error('discount_price')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
</div>
<div class="admin-form-section">
    <h2 class="admin-form-section-title">Системные требования</h2>

            <div class="admin-form-group">
                <label for="system_requirements">Системные требования</label>
                <textarea id="system_requirements" name="system_requirements" rows="5" required>{{ old('system_requirements', $game->system_requirements) }}</textarea>
                @error('system_requirements')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="admin-form-group">
                <label for="recommended_requirements">Рекомендуемые требования</label>
                <textarea id="recommended_requirements" name="recommended_requirements" rows="5" required>{{ old('recommended_requirements', $game->recommended_requirements) }}</textarea>
                @error('recommended_requirements')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
</div>
<div class="admin-form-section">
    <h2 class="admin-form-section-title">Жанры</h2>

            <div class="admin-form-group">
                <div class="admin-form-genres">
                    @foreach($genres as $genre)
                        <div class="admin-form-genre">
                            <input type="checkbox" 
                                   id="genre-{{ $genre->id }}" 
                                   name="genres[]" 
                                   value="{{ $genre->id }}" 
                                   {{ in_array($genre->id, old('genres', $game->genres->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label for="genre-{{ $genre->id }}">{{ $genre->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('genres')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
</div>
<div class="admin-form-section">      
     <h2 class="admin-form-section-title">Изображения</h2> 

            <div class="admin-form-group">
                <label>Текущие изображения ({{ $game->images->count() }}/6)</label>
                <div class="admin-form-images">
                    @foreach($game->images as $image)
                        <div class="admin-form-image">
                            <img src="{{ asset('images/games/' . $image->image_path) }}" alt="Изображение {{ $loop->index + 1 }}">
                            <div class="admin-form-image-actions">
                                <div class="admin-form-checkbox">
                                    <label for="primary-{{ $image->id }}">Основное</label>
                                    <input type="radio" id="primary-{{ $image->id }}" name="primary_image" class="admin-form-btn" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }}> 
                                </div>
                                <div class="admin-form-checkbox">
                                    <label for="delete-{{ $image->id }}">Удалить</label>
                                    <input type="checkbox" id="delete-{{ $image->id }}" name="delete_images[]" value="{{ $image->id }}" class="admin-form-btn">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="admin-form-group">
                <label class="label1">Добавить новые изображения</label>
                <input type="file" name="new_images[]" multiple accept="image/*" class="admin-file-input">
                <p class="admin-form-help">Максимум 6 изображений всего. Сейчас у игры {{ $game->images->count() }} изображений.</p>
                @error('new_images')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
</div>

<div class="admin-form-section">
                <h2 class="admin-form-section-title">Дополнительные настройки</h2>
                
                <div class="admin-form-options">
                    <div class="admin-form-option">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $game->is_featured) ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_featured">Популярная игра</label>
                        </div>
                    </div>
                    
                    <div class="admin-form-option">
                        <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new', $game->is_new) ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_new">Новинка</label>
                        </div>
                    </div>
                    
                    <div class="admin-form-option">
                        <input type="checkbox" id="is_on_sale" name="is_on_sale" value="1" {{ old('is_on_sale', $game->is_on_sale) ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_on_sale">Распродажа</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
                <a href="{{ route('admin.games') }}" class="admin-form-cancel">ОТМЕНА</a>
            </div>
</div>
        </form>
    </div>
</div>
@endsection
