@extends('layouts.app')

@section('content')
    <h1 class="search-title">Результаты поиска: "{{ $query }}"</h1>
    
    @if($games->isEmpty())
        <div class="search-empty">
            <p>По вашему запросу ничего не найдено.</p>
        </div>
    @else
        <div class="game-grid">
            @foreach($games as $game)
                <game-card :game="{{ json_encode($game) }}" @message="showMessage"></game-card>
            @endforeach
        </div>
        
        <div class="search-pagination">
            {{ $games->appends(['query' => $query])->links() }}
        </div>
    @endif
@endsection
