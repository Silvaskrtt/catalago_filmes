@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Cabeçalho com logo -->
        <div class="text-center mb-10 fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-red-600 to-red-700 mb-6 shadow-lg shadow-red-900/30">
                <span class="text-4xl">🎬</span>
            </div>
            <h1 id="login-title" class="text-3xl font-bold text-white mb-2">Catálogo de Filmes</h1>
            <p id="login-welcome" class="text-gray-400">Bem-vindo ao seu catálogo pessoal</p>
        </div>

        <!-- Formulário -->
        <div class="bg-gray-900 rounded-2xl p-8 shadow-xl border border-gray-800 fade-in" style="animation-delay: 0.1s">
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-green-500 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="seu@email.com"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Senha</label>
                        <input id="password" type="password" name="password" required
                               placeholder="••••••••"
                               class="w-full px-4 py-3 bg-gray-800 border @error('password') border-red-500 @else border-gray-700 @enderror rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lembrar-me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember"
                                   class="rounded bg-gray-700 border-gray-600 text-red-600 focus:ring-red-500 focus:ring-offset-gray-900 focus:ring-2">
                            <span class="ml-2 text-sm text-gray-400">{{ __('Lembrar-me') }}</span>
                        </label>
                    </div>

                    <!-- Botão Entrar -->
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-red-900/30 mt-2">
                        Entrar
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
                <p class="text-center text-gray-500 text-sm mt-6">
                    Não tem conta?
                    <a href="{{ route('register') }}" class="text-red-500 hover:text-red-400 font-medium transition-colors">
                        Cadastre-se
                    </a>
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
