@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Filme</h1>

    <form action="{{ route('movies.update', $movie) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror"
                   id="title" name="title" value="{{ old('title', $movie->title) }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="genre_id" class="form-label">Gênero</label>
            <select class="form-control @error('genre_id') is-invalid @enderror"
                    id="genre_id" name="genre_id">
                <option value="">Selecione um gênero</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}"
                        {{ old('genre_id', $movie->genre_id) == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
            @error('genre_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="review" class="form-label">Resenha</label>
            <textarea class="form-control @error('review') is-invalid @enderror"
                      id="review" name="review" rows="5">{{ old('review', $movie->review) }}</textarea>
            @error('review')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Avaliação (1-5)</label>
            <select class="form-control @error('rating') is-invalid @enderror"
                    id="rating" name="rating">
                <option value="">Selecione uma nota</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}"
                        {{ old('rating', $movie->rating) == $i ? 'selected' : '' }}>
                        {{ $i }} estrela{{ $i > 1 ? 's' : '' }}
                    </option>
                @endfor
            </select>
            @error('rating')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
