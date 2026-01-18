<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    /**
     * Construtor do controller
     */
    public function __construct()
    {
        // Aplicar middleware de autenticação em todos os métodos
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mostrar apenas filmes do usuário logado com gêneros
        $movies = Auth::user()->movies()->with('genre')->latest()->get();
        $genres = Genre::all(); // Para os filtros

        return view('movies.index', compact('movies', 'genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::all();
        return view('movies.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        // Criar filme associado ao usuário logado
        Auth::user()->movies()->create($request->all());

        return redirect()->route('movies.index')
            ->with('success', 'Filme adicionado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        // Verificar se o filme pertence ao usuário logado
        if ($movie->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para editar este filme');
        }

        $genres = Genre::all();
        return view('movies.edit', compact('movie', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        // Verificar se o filme pertence ao usuário logado
        if ($movie->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para atualizar este filme');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $movie->update($request->all());

        return redirect()->route('movies.index')
            ->with('success', 'Filme atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        // Verificar se o filme pertence ao usuário logado
        if ($movie->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para excluir este filme');
        }

        $movie->delete();

        return redirect()->route('movies.index')
            ->with('success', 'Filme excluído com sucesso!');
    }
}
