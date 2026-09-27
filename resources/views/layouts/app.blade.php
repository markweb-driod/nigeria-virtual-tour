<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nigeria Virtual Tour') — Explore Nigeria in 360°</title>
    <meta name="description" content="@yield('meta_description', 'Discover Nigeria\'s most breathtaking tourist attractions in immersive 360° panoramic virtual tours.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-bglight" x-data>

    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
         x-data="{ scrolled: false, mobileOpen: false }"
         @scroll.window="scrolled = window.scrollY > 20">

        <div class="px-4 md:px-8"
             :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-md py-3' : 'bg-transparent py-5'">
            <div class="max-w-7xl mx-auto flex items-center justify-between">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-forest rounded-xl flex items-center justify-center shadow-md">
                        <span class="text-gold font-bold text-lg leading-none">N</span>
                    </div>
                    <div>
                        <span class="font-heading font-bold text-base leading-tight block"
                              :class="scrolled ? 'text-dark' : 'text-white'">NigeriaVirtual</span>
                        <span class="text-xs font-medium block -mt-0.5"
                              :class="scrolled ? 'text-forest' : 'text-gold'">Tour</span>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-medium transition-colors"
                       :class="scrolled ? 'text-gray-700 hover:text-forest' : 'text-white/90 hover:text-white'">Home</a>
                    <a href="{{ route('explore') }}" class="text-sm font-medium transition-colors"
                       :class="scrolled ? 'text-gray-700 hover:text-forest' : 'text-white/90 hover:text-white'">Explore</a>
                    <a href="{{ route('explore') }}?tour=1" class="text-sm font-medium transition-colors"
                       :class="scrolled ? 'text-gray-700 hover:text-forest' : 'text-white/90 hover:text-white'">Virtual Tours</a>
                </div>

                {{-- CTA --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('explore') }}" class="btn-primary btn-sm text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                        </svg>
                        Start Exploring
                    </a>
                </div>

                {{-- Mobile menu toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg"
                        :class="scrolled ? 'text-dark' : 'text-white'">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileOpen" x-transition class="md:hidden mt-4 pb-4 space-y-2 border-t border-white/20">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm font-medium text-white hover:text-gold">Home</a>
                <a href="{{ route('explore') }}" class="block px-4 py-2 text-sm font-medium text-white hover:text-gold">Explore</a>
                <a href="{{ route('explore') }}?tour=1" class="block px-4 py-2 text-sm font-medium text-white hover:text-gold">Virtual Tours</a>
                <a href="{{ route('explore') }}" class="block mt-2 mx-4 btn-primary text-center">Start Exploring</a>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-dark text-gray-300 mt-24">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 bg-forest rounded-xl flex items-center justify-center">
                            <span class="text-gold font-bold text-lg">N</span>
                        </div>
                        <div>
                            <span class="font-heading font-bold text-white text-base block">NigeriaVirtual</span>
                            <span class="text-gold text-xs font-medium block -mt-0.5">Tour</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-sm">
                        Discover Nigeria's most breathtaking tourist attractions through immersive 360° panoramic virtual tours. Explore from anywhere in the world.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">Explore</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('explore') }}" class="hover:text-gold transition-colors">All Attractions</a></li>
                        <li><a href="{{ route('explore') }}?tour=1" class="hover:text-gold transition-colors">Virtual Tours</a></li>
                        <li><a href="{{ route('explore') }}?featured=1" class="hover:text-gold transition-colors">Featured Destinations</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">Categories</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('attractions.category', 'nature') }}" class="hover:text-gold transition-colors">Nature</a></li>
                        <li><a href="{{ route('attractions.category', 'heritage') }}" class="hover:text-gold transition-colors">Heritage</a></li>
                        <li><a href="{{ route('attractions.category', 'historical') }}" class="hover:text-gold transition-colors">Historical</a></li>
                        <li><a href="{{ route('attractions.category', 'parks') }}" class="hover:text-gold transition-colors">Parks &amp; Resorts</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-500">© {{ date('Y') }} Nigeria Virtual Tour. A 360° Tourism Research Project.</p>
                <a href="{{ route('login') }}" class="text-xs text-gray-600 hover:text-gold transition-colors">Admin</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
