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
                    <div class="admin-form-toggles">
                        @foreach($genres as $genre)
                            <div class="admin-form-toggle">
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
                    <p class="admin-form-help">Выберите несколько изображений для игры (скриншоты, обложка и т.д.)</p>
                </div>
                
                <div class="admin-form-group">
                    <label for="primary_image">Основное изображение</label>
                    <input type="number" id="primary_image" name="primary_image" value="{{ old('primary_image', 0) }}" min="0" required>
                    <p class="admin-form-help">Укажите номер изображения, которое будет основным (0 - первое, 1 - второе и т.д.)</p>
                </div>
            </div>
            
            <div class="admin-form-section">
                <h2 class="admin-form-section-title">Дополнительные настройки</h2>
                
                <div class="admin-form-checkboxes">
                    <div class="admin-form-toggle">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_featured">Популярная игра</label>
                        </div>
                    </div>
                    
                    <div class="admin-form-toggle">
                        <input type="checkbox" id="is_new" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_new">Новинка</label>
                        </div>
                    </div>
                    
                    <div class="admin-form-toggle">
                        <input type="checkbox" id="is_on_sale" name="is_on_sale" value="1" {{ old('is_on_sale') ? 'checked' : '' }}>
                        <div class="admin-form-option-content">
                            <label for="is_on_sale">Распродажа</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="admin-form-actions">
                <button type="submit" class="admin-form-submit">Добавить игру</button>
                <a href="{{ route('admin.games') }}" class="admin-form-cancel">Отмена</a>
            </div>
        </form>
    </div>
</div>

<style>
.admin-form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e0e0e0;
}

.admin-form-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.admin-form-section-title {
    font-size: 1.25rem;
    color: #393a43;
    margin-bottom: 1rem;
    font-weight: 600;
}

.admin-form-genres {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.admin-form-genre {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background-color: #f5f5f5;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.admin-form-genre:hover {
    background-color: #e8e8e8;
    border-color: #393a43;
}

.admin-form-genre input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: #393a43;
}

.admin-form-genre label {
    font-weight: 500;
    cursor: pointer;
    color: #333;
}

.admin-form-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.admin-form-option {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background-color:rgb(187, 187, 187);
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.admin-form-option:hover {
    background-color: #e8e8e8;
    border-color: #393a43;
}

.admin-form-option input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-top: 0.25rem;
    accent-color: #393a43;
}

.admin-form-option-content label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.25rem;
    cursor: pointer;
    color: #333;
}

.admin-form-option-content p {
    font-size: 0.875rem;
    color: #666;
    margin: 0;
}

.admin-file-input {
    padding: 1rem;
    background-color: #f5f5f5;
    border-radius: 8px;
    border: 2px dashed #ccc;
    width: 100%;
}

.admin-empty-genres {
    padding: 2rem;
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    text-align: center;
}

.admin-empty-genres p {
    margin-bottom: 0.5rem;
    color: #856404;
}

.admin-empty-genres code {
    background-color: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: monospace;
    color: #e83e8c;
}

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

@media (max-width: 768px) {
    .admin-form-genres,
    .admin-form-options {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
