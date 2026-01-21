@extends('layouts.app')

@section('title', 'Editar Filme')

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
            <h1 id="form-title" class="text-lg font-semibold text-white">Editar Filme</h1>
            <div class="w-16"></div>
        </div>
    </header>

    <!-- Form Content -->
    <main class="flex-1 overflow-auto px-6 py-8">
        <div class="max-w-2xl mx-auto">
            <form id="movie-form" method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data" class="space-y-6 fade-in">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Título do Filme *</label>
                    <input type="text" id="movie-title" name="title" value="{{ old('title', $movie->title) }}" required
                           placeholder="Ex: O Poderoso Chefão"
                           class="w-full px-4 py-3 bg-gray-800 border @error('title') border-red-500 @else border-gray-700 @enderror rounded-xl text-white placeholder-gray-500 input-focus">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="genre_id" class="block text-sm font-medium text-gray-300 mb-2">Gênero *</label>

                    <div class="flex gap-2 mb-2">
                        <select id="movie-genre" name="genre_id" required
                                class="flex-1 px-4 py-3 bg-gray-800 border @error('genre_id') border-red-500 @else border-gray-700 @enderror rounded-xl text-white input-focus appearance-none cursor-pointer">
                            <option value="">Selecione um gênero</option>
                            @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ old('genre_id', $movie->genre_id) == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                            @endforeach
                        </select>

                        <button type="button" id="add-genre-btn"
                                class="px-4 py-3 bg-gray-700 hover:bg-gray-600 text-gray-300 font-medium rounded-xl transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Novo
                        </button>
                    </div>

                    @error('genre_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cover_image" class="block text-sm font-medium text-gray-300 mb-2">Capa do Filme</label>

                    <!-- Preview da imagem atual -->
                    @if($movie->hasCover())
                    <div class="mb-4">
                        <img src="{{ $movie->cover_url }}"
                            alt="Capa atual"
                            class="w-32 object-cover rounded-lg mb-2 border border-gray-700">
                        <p class="text-gray-400 text-sm mb-2">Capa atual</p>
                        <div class="flex items-center">
                            <input type="checkbox" id="remove_cover" name="remove_cover" value="1"
                            class="mr-2 rounded bg-gray-800 border-gray-700 text-red-600 focus:ring-red-600 focus:ring-offset-gray-900">
                        <label for="remove_cover" class="text-sm text-gray-300">Remover capa atual</label>
                    </div>
                </div>
                @endif

                <!-- Campo para nova imagem -->
                <div class="mt-2">
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">
                </div>
                    <p class="text-gray-500 text-sm mt-1">Deixe em branco para manter a capa atual. Formatos: JPEG, PNG, GIF, WebP. Tamanho máximo: 2MB</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Avaliação *</label>
                    <div id="rating-input" class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})"
                                class="star text-3xl {{ old('rating', $movie->rating) >= $i ? 'text-amber-400' : 'text-gray-600' }} hover:text-amber-400"
                                data-rating="{{ $i }}">
                            ★
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" id="movie-rating" name="rating" value="{{ old('rating', $movie->rating) }}">
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="review" class="block text-sm font-medium text-gray-300 mb-2">Crítica Pessoal *</label>
                    <textarea id="movie-review" name="review" required rows="5"
                              placeholder="Escreva sua análise sobre o filme..."
                              class="w-full px-4 py-3 bg-gray-800 border @error('review') border-red-500 @else border-gray-700 @enderror rounded-xl text-white placeholder-gray-500 input-focus resize-none">{{ old('review', $movie->review) }}</textarea>
                    @error('review')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 pt-4">
                    <a href="{{ route('movies.index') }}" class="flex-1 py-3.5 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition-colors text-center">
                        Cancelar
                    </a>
                    <button type="submit" id="save-btn" class="flex-1 py-3.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl btn-primary">
                        Salvar Alterações
                    </button>
                </div>
            </form>

            <!-- Delete Button -->
            <div id="delete-section" class="mt-6 pt-6 border-t border-gray-800">
                <form id="delete-form" method="POST" action="{{ route('movies.destroy', $movie) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="showDeleteModal('{{ route('movies.destroy', $movie) }}')"
                            id="delete-btn" class="w-full py-3 border border-red-600/30 text-red-500 font-medium rounded-xl hover:bg-red-600/10 transition-colors">
                        Excluir Filme
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Modal para adicionar novo gênero (ESTE É O QUE ESTÁ FALTANDO) -->
    <div id="genre-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden px-4">
        <div class="bg-gray-900 rounded-2xl p-6 max-w-md w-full border border-gray-800 shadow-2xl fade-in">
            <h3 class="text-xl font-bold text-white mb-4">Adicionar Novo Gênero</h3>

            <div class="mb-4">
                <label for="new-genre-name" class="block text-sm font-medium text-gray-300 mb-2">Nome do Gênero</label>
                <input type="text" id="new-genre-name"
                       class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 input-focus"
                       placeholder="Ex: Ficção Científica">
                <p id="genre-error" class="text-red-500 text-sm mt-1 hidden"></p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeGenreModal()"
                        class="flex-1 py-3 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="saveNewGenre()" id="save-genre-btn"
                        class="flex-1 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors">
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal (já existe no layout principal) -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addGenreBtn = document.getElementById('add-genre-btn');
        if (addGenreBtn) {
            addGenreBtn.addEventListener('click', openGenreModal);
        }

        // Set initial rating
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

    function openGenreModal() {
        document.getElementById('genre-modal').classList.remove('hidden');
        document.getElementById('new-genre-name').focus();
    }

    function closeGenreModal() {
        document.getElementById('genre-modal').classList.add('hidden');
        document.getElementById('new-genre-name').value = '';
        document.getElementById('genre-error').classList.add('hidden');
        document.getElementById('genre-error').textContent = '';
    }

    function saveNewGenre() {
        const genreName = document.getElementById('new-genre-name').value.trim();
        const saveBtn = document.getElementById('save-genre-btn');
        const errorEl = document.getElementById('genre-error');

        if (!genreName) {
            errorEl.textContent = 'Por favor, digite um nome para o gênero.';
            errorEl.classList.remove('hidden');
            return;
        }

        // Desabilitar botão durante a requisição
        saveBtn.disabled = true;
        saveBtn.textContent = 'Salvando...';
        errorEl.classList.add('hidden');

        // Enviar requisição AJAX
        fetch('{{ route("genres.store-ajax") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: genreName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Adicionar nova opção ao select
                const select = document.getElementById('movie-genre');
                const newOption = document.createElement('option');
                newOption.value = data.genre.id;
                newOption.textContent = data.genre.name;
                select.appendChild(newOption);

                // Selecionar o novo gênero
                select.value = data.genre.id;

                // Fechar modal
                closeGenreModal();

                // Mostrar mensagem de sucesso
                showToast(data.message);
            } else {
                errorEl.textContent = data.message;
                errorEl.classList.remove('hidden');
            }
        })
        .catch(error => {
            errorEl.textContent = 'Erro ao conectar com o servidor.';
            errorEl.classList.remove('hidden');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Salvar';
        });
    }

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

    let deleteUrl = null;

    function showDeleteModal(url) {
        deleteUrl = url;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteUrl = null;
    }

    function confirmDelete() {
        if (!deleteUrl) return;

        const confirmBtn = document.getElementById('confirm-delete-btn');
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Excluindo...';

        // Submit the form
        const form = document.querySelector('.delete-form');
        if (form) {
            form.submit();
        }
    }
</script>
@endpush
