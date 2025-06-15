@extends('layouts.app')

@section('content')
    <div class="admin-form">
        <h1 class="admin-form-title">Редактирование игры</h1>
        
        @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
            
            <div class="admin-form-group">
                <label>Жанры</label>
                <div class="admin-form-checkboxes">
                    @foreach($genres as $genre)
                        <div class="admin-form-checkbox">
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
            
            <div class="admin-form-group">
                <label>Текущие изображения ({{ $game->images->count() }}/6)</label>
                <div class="admin-form-images">
                    @foreach($game->images as $image)
                        <div class="admin-form-image">
                            <img src="{{ asset('images/games/' . $image->image_path) }}" alt="Изображение {{ $loop->index + 1 }}">
                            <div class="admin-form-image-actions">
                                <div class="admin-form-checkbox">
                                    <input type="radio" id="primary-{{ $image->id }}" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }}>
                                    <label for="primary-{{ $image->id }}">Основное</label>
                                </div>
                                <div class="admin-form-checkbox">
                                    <input type="checkbox" id="delete-{{ $image->id }}" name="delete_images[]" value="{{ $image->id }}">
                                    <label for="delete-{{ $image->id }}">Удалить</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="admin-form-group">
                <label>Добавить новые изображения</label>
                <input type="file" name="new_images[]" multiple accept="image/*">
                <p class="admin-form-help">Максимум 6 изображений всего. Сейчас у игры {{ $game->images->count() }} изображений.</p>
                @error('new_images')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="admin-form-group">
                <div class="admin-form-checkbox">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $game->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured">Популярная игра</label>
                </div>
            </div>
            
            <div class="admin-form-group">
                <div class="admin-form-checkbox">
                    <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new', $game->is_new) ? 'checked' : '' }}>
                    <label for="is_new">Новинка</label>
                </div>
            </div>
            
            <div class="admin-form-group">
                <div class="admin-form-checkbox">
                    <input type="checkbox" id="is_on_sale" name="is_on_sale" value="1" {{ old('is_on_sale', $game->is_on_sale) ? 'checked' : '' }}>
                    <label for="is_on_sale">Распродажа</label>
                </div>
            </div>
            
            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">Сохранить изменения</button>
                <a href="{{ route('admin.games') }}" class="admin-form-cancel">Отмена</a>
            </div>
        </form>
    </div>

<style>
.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 1.5rem;
}

.admin-form-help {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.5rem;
}
</style>
@endsection
