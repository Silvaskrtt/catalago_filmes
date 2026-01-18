@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Meus Filmes</h1>

    <a href="{{ route('movies.create') }}" class="btn btn-primary mb-3">Novo Filme</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($movies as $movie)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $movie->title }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        Gênero: {{ $movie->genre->name }}
                    </h6>
                    <p class="card-text">{{ Str::limit($movie->review, 100) }}</p>
                    <p class="card-text">
                        <strong>Avaliação:</strong>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $movie->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                        ({{ $movie->rating }}/5)
                    </p>
                    <a href="{{ route('movies.edit', $movie) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
