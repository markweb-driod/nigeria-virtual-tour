@extends('layouts.app')
@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-forest-900 via-forest to-dark flex items-center justify-center px-4 py-24">
    <div class="w-full max-w-md">
        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-forest rounded-2xl shadow-lg mb-4">
                    <span class="text-gold font-bold text-3xl">N</span>
                </div>
                <h1 class="font-heading text-2xl font-bold text-dark">Administrator Login</h1>
                <p class="text-gray-500 text-sm mt-1">Nigeria Virtual Tour Platform</p>
            </div>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-input @error('email') border-red-400 @enderror"
                           placeholder="admin@example.com" required autofocus>
                    @error('email')
                    <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-input" placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-forest focus:ring-forest">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-3.5">
                    Sign in to Admin Panel
                </button>
            </form>
        </div>

        <p class="text-center text-white/60 text-sm mt-6">
            <a href="{{ route('home') }}" class="hover:text-gold transition-colors">← Back to Public Site</a>
        </p>
    </div>
</div>
@endsection
