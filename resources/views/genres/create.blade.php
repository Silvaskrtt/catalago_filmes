@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Gênero</h1>

    <form action="{{ route('genres.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome do Gênero</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('genres.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
