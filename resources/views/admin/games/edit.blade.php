@extends('layouts.app')

@section('content')
    <div class="admin-form">
        <h1 class="admin-form-title">Редактирование игры</h1>
        <div class="admin-content">
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
            
            <div class="admin-form-section">
                <label>Жанры</label>
                <div class="admin-form-toggles">
                        @foreach($genres as $genre)
                            <div class="admin-form-toggle">
                                <input type="checkbox" id="genre-{{ $genre->id }}" name="genres[]" value="{{ $genre->id }}" 
                                       @if(in_array($genre->id, old('genres', []))) checked @endif>
                                <label for="genre-{{ $genre->id }}">{{ $genre->name }}</label>
                            </div>
                        @endforeach
                    </div>
                @error('genres')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="admin-form-section">
                <h2 class="admin-form-section-title">Изображения</h2>
                
                <div class="admin-form-group">
                    <label for="images">Изображения игры</label>
                    <input type="file" id="images" name="images[]" multiple required accept="image/*" class="admin-file-input">
                    <p class="admin-form-help">Выберите несколько изображений для игры (скриншоты, обложка и т.д.)</p>
                </div>
                
                <div class="admin-form-group">
                    <label for="primary_image">Основное изображение</label>
                    <input type="number" id="primary_image" name="primary_image" value="{{ old('primary_image', 0) }}" min="0" required>
                    <p class="admin-form-help">Укажите номер изображения, которое будет основным (0 - первое, 1 - второе и т.д.)</p>
                </div>
            </div>
            
                <h2 class="admin-form-section-title">Дополнительные настройки</h2>
            
            <div class="admin-form-checkboxes">
                <div class="admin-form-toggle">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $game->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured">Популярная игра</label>
                </div>
            
            
 
                <div class="admin-form-toggle">
                    <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new', $game->is_new) ? 'checked' : '' }}>
                    <label for="is_new">Новинка</label>
                </div>

            

                <div class="admin-form-toggle">
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
    </div>
@endsection
