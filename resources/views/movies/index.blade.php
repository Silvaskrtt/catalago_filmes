@extends('layouts.app')

@section('title', 'Meus Filmes')

@section('content')
<!-- MAIN CATALOG SCREEN -->
<div id="catalog-screen" class="screen active grid grid-rows-[auto_1fr_auto] min-h-screen">
    <!-- Header -->
    <header class="bg-gray-900 border-b border-gray-800 px-6 py-4 row-start-1">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-600 to-red-700 flex items-center justify-center shadow-lg">
                    <span class="text-xl">🎬</span>
                </div>
                <h1 id="header-title" class="text-xl font-bold text-white">Catálogo de Filmes</h1>
            </div>
            <div class="flex items-center gap-4">
                <span id="user-email" class="text-gray-400 text-sm hidden sm:block">{{ auth()->user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-white transition-colors text-sm font-medium">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="row-start-2 overflow-auto px-6 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-white">Meus Filmes</h2>
                    <p id="movie-count" class="text-gray-400 text-sm mt-1">
                        {{ $movies->count() }} filme{{ $movies->count() !== 1 ? 's' : '' }} cadastrado{{ $movies->count() !== 1 ? 's' : '' }}
                    </p>
                </div>
                <a href="{{ route('movies.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Filme
                </a>
            </div>

            <!-- Filter Tabs -->
            <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                <button onclick="filterMovies('all')" data-filter="all" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-red-600 text-white transition-colors whitespace-nowrap">
                    Todos
                </button>
                @foreach($genres as $genre)
                <button onclick="filterMovies('{{ $genre->id }}')" data-filter="{{ $genre->id }}"
                        class="filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-gray-800 text-gray-300 hover:bg-gray-700 transition-colors whitespace-nowrap">
                    {{ $genre->name }}
                </button>
                @endforeach
            </div>

            <!-- Movies Grid -->
            <div id="movies-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($movies as $movie)
                <div class="movie-card bg-gray-900 rounded-2xl p-6 border border-gray-800 card-hover cursor-pointer fade-in"
                     data-movie-id="{{ $movie->id }}"
                     data-genre-id="{{ $movie->genre_id }}"
                     onclick="window.location.href='{{ route('movies.edit', $movie) }}'">

                    @php
                        $genreColors = [
                            1 => 'bg-blue-600/20 text-blue-400',   // Ação
                            2 => 'bg-green-600/20 text-green-400', // Comédia
                            3 => 'bg-purple-600/20 text-purple-400', // Terror
                        ];
                        $genreColor = $genreColors[$movie->genre_id] ?? 'bg-gray-700 text-gray-300';
                    @endphp

                    <!-- Imagem da capa -->
                    <div class="mb-4 relative">
                        <div class="w-full rounded-xl overflow-hidden bg-gray-800">
                            <img src="{{ $movie->cover_url }}"
                                alt="{{ $movie->title }}"
                                class="w-full h-full object-cover"
                                onerror="this.src='{{ asset('images/default-movie-cover.jpg') }}'">
                        </div>
                        <span class="absolute top-2 right-2 px-3 py-1 rounded-full text-xs font-medium {{ $genreColor }}">
                            {{ $movie->genre->name }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $genreColor }}">
                            {{ $movie->genre->name }}
                        </span>
                        <span class="text-amber-400 text-lg tracking-wide">
                            {{ str_repeat('★', $movie->rating) }}{{ str_repeat('☆', 5 - $movie->rating) }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3 line-clamp-2">{{ $movie->title }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        {{ strlen($movie->review) > 100 ? substr($movie->review, 0, 100) . '...' : $movie->review }}
                    </p>
                </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if($movies->isEmpty())
            <div id="empty-state" class="text-center py-16">
                <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl opacity-50">🎥</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">Nenhum filme cadastrado</h3>
                <p class="text-gray-500 mb-6">Comece adicionando seu primeiro filme ao catálogo</p>
                <a href="{{ route('movies.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Primeiro Filme
                </a>
            </div>
            @else
            <div id="empty-state" class="text-center py-16 hidden">
                <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl opacity-50">🎥</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">Nenhum filme encontrado</h3>
                <p class="text-gray-500 mb-6">Tente alterar o filtro selecionado</p>
            </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 px-6 py-4 row-start-3">
        <p id="footer-text" class="text-center text-gray-500 text-sm">
            © {{ date('Y') }} Catálogo de Filmes
        </p>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    // Movie filtering functionality
    function filterMovies(filter) {
        const movieCards = document.querySelectorAll('.movie-card');
        const emptyState = document.getElementById('empty-state');
        let visibleCount = 0;

        movieCards.forEach(card => {
            const genreId = card.dataset.genreId;
            if (filter === 'all' || genreId === filter) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Update filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.dataset.filter === filter) {
                btn.classList.remove('bg-gray-800', 'text-gray-300');
                btn.classList.add('bg-red-600', 'text-white');
            } else {
                btn.classList.remove('bg-red-600', 'text-white');
                btn.classList.add('bg-gray-800', 'text-gray-300');
            }
        });

        // Show/hide empty state
        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Show toast if there's a success message
        @if(session('success'))
            showToast('{{ session('success') }}');
        @endif

        @if(session('error'))
            showToast('{{ session('error') }}');
        @endif
    });
</script>
@endpush
