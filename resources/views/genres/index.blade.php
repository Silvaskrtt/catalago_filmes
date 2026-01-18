@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gêneros</h1>

    <a href="{{ route('genres.create') }}" class="btn btn-primary mb-3">Novo Gênero</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($genres as $genre)
            <tr>
                <td>{{ $genre->id }}</td>
                <td>{{ $genre->name }}</td>
                <td>
                    <a href="{{ route('genres.edit', $genre) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('genres.destroy', $genre) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
