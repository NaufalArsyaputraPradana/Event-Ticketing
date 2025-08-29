@extends('layouts.app')

@section('title', 'Login - EventTick')

@section('content')
    <!-- Login -->
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-purple-100">
                    <i class="fas fa-ticket-alt text-2xl text-purple-600"></i>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Login ke Akun Anda
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Atau
                    <a href="{{ route('register') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        daftar akun baru
                    </a>
                </p>
            </div>

            <!-- Form Login -->
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address
                        </label>
                        <input id="email" name="email" type="email" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
                            placeholder="Masukkan email Anda">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <input id="password" name="password" type="password" required
                            class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 focus:z-10 sm:text-sm"
                            placeholder="Masukkan password Anda">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-purple-500 group-hover:text-purple-400"></i>
                        </span>
                        Login
                    </button>
                </div>
            </form>

            <!-- Kembali ke Home -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Home
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
