<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Catálogo de Filmes - {{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>@view-transition { navigation: auto; }</style>
</head>
<body class="h-full bg-gray-950 text-gray-100 overflow-auto">
    <div id="app-wrapper" class="w-full h-full flex flex-col">
        @yield('content')
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 px-6 py-3 bg-gray-800 text-white rounded-xl shadow-lg border border-gray-700 hidden z-50">
        <span id="toast-message"></span>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden px-4">
        <div class="bg-gray-900 rounded-2xl p-6 max-w-sm w-full border border-gray-800 shadow-2xl fade-in">
            <h3 class="text-xl font-bold text-white mb-2">Confirmar Exclusão</h3>
            <p class="text-gray-400 mb-6">Tem certeza que deseja excluir este filme? Esta ação não pode ser desfeita.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-3 bg-gray-800 text-gray-300 font-semibold rounded-xl hover:bg-gray-700 transition-colors">Cancelar</button>
                <button onclick="confirmDelete()" id="confirm-delete-btn" class="flex-1 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors">Excluir</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/movies.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
