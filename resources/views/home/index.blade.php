@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Temukan Destinasi Wisata Terbaik
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Jelajahi keindahan Indonesia dari Sabang sampai Merauke
            </p>
            <a href="{{ route('public.destinations') }}"
               class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                Jelajahi Sekarang
            </a>
        </div>
    </div>
</div>

<!-- Categories Section -->
@if($categories->count() > 0)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Kategori Wisata</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('public.destinations', ['category' => $category->id]) }}"
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition text-center group">
                <div class="text-4xl mb-3">
                    @switch($category->slug)
                        @case('pantai')
                            🏖️
                            @break
                        @case('gunung')
                            ⛰️
                            @break
                        @case('kuliner')
                            🍽️
                            @break
                        @case('sejarah')
                            🏛️
                            @break
                        @case('taman-bermain')
                            🎡
                            @break
                        @default
                            📍
                    @endswitch
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition">
                    {{ $category->name }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $category->destinations_count }} destinasi
                </p>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- Featured Destinations -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Destinasi Terbaru</h2>
            <a href="{{ route('public.destinations') }}"
               class="text-blue-600 hover:text-blue-800 font-semibold">
                Lihat Semua →
            </a>
        </div>

        @if($featuredDestinations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredDestinations as $destination)
                    <a href="{{ route('public.destination.show', $destination) }}"
                       class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                        @if($destination->image)
                            <img src="{{ asset('images/' . $destination->image) }}"
                                 alt="{{ $destination->name }}"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-500 text-4xl">📍</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mb-2">
                                {{ $destination->category->name ?? 'Uncategorized' }}
                            </span>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">
                                {{ $destination->name }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                📍 {{ $destination->location }}
                            </p>
                            <p class="text-gray-500 text-sm mb-3 line-clamp-2">
                                {{ Str::limit($destination->description, 100) }}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-600 font-bold text-lg">
                                    Rp {{ number_format($destination->price, 0, ',', '.') }}
                                </span>
                                <span class="text-blue-600 font-semibold group-hover:translate-x-1 transition">
                                    Lihat Detail →
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">🏝️</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Destinasi</h3>
                <p class="text-gray-600">
                    Destinasi wisata akan segera ditambahkan. Silakan cek kembali nanti!
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Call to Action -->
<div class="bg-blue-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Siap Menjelajah?</h2>
        <p class="text-xl mb-8 text-blue-100">
            Temukan destinasi wisata impian Anda dan mulai petualangan yang tak terlupakan
        </p>
        <a href="{{ route('public.destinations') }}"
           class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
            Jelajahi Destinasi
        </a>
    </div>
</div>
@endsection
