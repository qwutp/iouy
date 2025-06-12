@extends('layouts.app')

@section('content')
    <div class="games-container">
        <aside class="games-sidebar">
            <filter-sidebar 
                :genres="{{ json_encode($genres) }}" 
                :initial-filters="{{ json_encode(request()->all()) }}"
                @filters-applied="(filters) => window.location.href = '{{ route('games.index') }}?' + new URLSearchParams(filters).toString()"
            ></filter-sidebar>
        </aside>
        
        <div class="games-content">
            <h1 class="games-title">Каталог игр</h1>
            
            @if($games->isEmpty())
                <div class="games-empty">
                    <p>По вашему запросу ничего не найдено.</p>
                </div>
            @else
                <div class="game-grid">
                    @foreach($games as $game)
                        <game-card :game="{{ json_encode($game) }}" @message="showMessage"></game-card>
                    @endforeach
                </div>
                
                <div class="games-pagination">
                    {{ $games->appends(request()->all())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
