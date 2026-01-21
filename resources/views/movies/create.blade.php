@extends('layouts.app')

@section('title', 'Adicionar Filme')

@section('content')
<!-- MOVIE FORM SCREEN -->
<div id="movie-form-screen" class="screen active flex-col h-full w-full">
    <!-- Header -->
    <header class="bg-gray-900 border-b border-gray-800 px-6 py-4 flex-shrink-0">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <a href="{{ route('movies.index') }}" class="flex items-center gap-2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar
            </a>
            <h1 id="form-title" class="text-lg font-semibold text-white">Adicionar Filme</h1>
            <div class="w-16"></div>
        </div>
    </header>

    <!-- Form Content -->
    <main class="flex-1 overflow-auto px-6 py-8">
        <div class="max-w-2xl mx-auto">
            <form id="movie-form" method="POST" action="{{ route('movies.store') }}" enctype="multipart/form-data" class="space-y-6 fade-in">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Título do Filme *</label>
                    <input type="text" id="movie-title" name="title" value="{{ old('title') }}" required
                           placeholder="Ex: O Poderoso Chefão"
                           class="w-full px-4 py-3 bg-gray-800 border @error('title') border-red-500 @else border-gray-700 @enderror rounded-xl text-white placeholder-gray-500 input-focus">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="genre_id" class="block text-sm font-medium text-gray-300 mb-2">Gênero *</label>
                    <select id="movie-genre" name="genre_id" required
                            class="w-full px-4 py-3 bg-gray-800 border @error('genre_id') border-red-500 @else border-gray-700 @enderror rounded-xl text-white input-focus appearance-none cursor-pointer">
                        <option value="">Selecione um gênero</option>
                        @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('genre_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-300 mb-2">Capa do Filme</label>
                    <div class="mt-2">
                        <input type="file" id="cover_image" name="cover_image" accept="image/*"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">
                    </div>
                    <p class="text-gray-500 text-sm mt-1">Formatos: JPEG, PNG, GIF, WebP. Tamanho máximo: 2MB</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Avaliação *</label>
                    <div id="rating-input" class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})"
                                class="star text-3xl text-gray-600 hover:text-amber-400"
                                data-rating="{{ $i }}">
                            ★
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" id="movie-rating" name="rating" value="{{ old('rating', 0) }}">
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="review" class="block text-sm font-medium text-gray-300 mb-2">Crítica Pessoal *</label>
                    <textarea id="movie-review" name="review" required rows="5"
                              placeholder="Escreva sua análise sobre o filme..."
                              class="w-full px-4 py-3 bg-gray-800 border @error('review') border-red-500 @else border-gray-700 @enderror rounded-xl text-white placeholder-gray-500 input-focus resize-none">{{ old('review') }}</textarea>
                    @error('review')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 pt-4">
                    <a href="{{ route('movies.index') }}" class="flex-1 py-3.5 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition-colors text-center">
                        Cancelar
                    </a>
                    <button type="submit" id="save-btn" class="flex-1 py-3.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl btn-primary">
                        Salvar Filme
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial rating from hidden input
        const initialRating = parseInt(document.getElementById('movie-rating').value) || 0;
        setRating(initialRating);

        // Form validation
        const form = document.getElementById('movie-form');
        form.addEventListener('submit', function(e) {
            const rating = parseInt(document.getElementById('movie-rating').value);
            if (rating === 0) {
                e.preventDefault();
                showToast('Por favor, selecione uma avaliação');
                return;
            }

            const saveBtn = document.getElementById('save-btn');
            saveBtn.disabled = true;
            saveBtn.textContent = 'Salvando...';
        });

        // Show validation errors
        @if($errors->any())
            showToast('Por favor, corrija os erros no formulário');
        @endif
    });

    function setRating(rating) {
        document.getElementById('movie-rating').value = rating;
        updateRatingDisplay();
    }

    function updateRatingDisplay() {
        const stars = document.querySelectorAll('#rating-input .star');
        const currentRating = parseInt(document.getElementById('movie-rating').value);

        stars.forEach((star, index) => {
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= currentRating) {
                star.classList.remove('text-gray-600');
                star.classList.add('text-amber-400');
            } else {
                star.classList.remove('text-amber-400');
                star.classList.add('text-gray-600');
            }
        });
    }
</script>
@endpush
