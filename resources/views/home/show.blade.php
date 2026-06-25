@extends('layouts.public')

@section('title', $destination->name)

@section('content')
<!-- Breadcrumb -->
<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Beranda</a>
                </li>
                <li>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('public.destinations') }}" class="text-gray-500 hover:text-gray-700">Destinasi</a>
                </li>
                <li>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-700 font-medium">{{ $destination->name }}</span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Destination Detail -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Image -->
            <div class="mb-6">
                @if($destination->image)
                    <img src="{{ asset('images/' . $destination->image) }}"
                         alt="{{ $destination->name }}"
                         class="w-full h-96 object-cover rounded-lg shadow-lg">
                @else
                    <div class="w-full h-96 bg-gray-300 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500 text-6xl">📍</span>
                    </div>
                @endif
            </div>

            <!-- Title and Category -->
            <div class="mb-6">
                <span class="inline-block px-4 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 mb-3">
                    {{ $destination->category->name ?? 'Uncategorized' }}
                </span>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $destination->name }}</h1>
                <div class="flex items-center text-gray-600 text-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $destination->location }}
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi</h2>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $destination->description ?: 'Tidak ada deskripsi untuk destinasi ini.' }}
                </p>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Price Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 sticky top-4">
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-2">Harga Tiket Masuk</p>
                    <p class="text-3xl font-bold text-blue-600">
                        Rp {{ number_format($destination->price, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">per orang</p>
                </div>

                <div class="border-t pt-4 space-y-3">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Lokasi mudah diakses</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Fasilitas lengkap</span>
                    </div>
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Cocok untuk keluarga</span>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('public.destinations') }}"
               class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-center px-6 py-3 rounded-lg font-semibold transition">
                ← Kembali ke Daftar Destinasi
            </a>
        </div>
    </div>

    <!-- Related Destinations -->
    @if($relatedDestinations->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Destinasi Serupa</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedDestinations as $related)
                    <a href="{{ route('public.destination.show', $related) }}"
                       class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                        @if($related->image)
                            <img src="{{ asset('images/' . $related->image) }}"
                                 alt="{{ $related->name }}"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-500 text-4xl">📍</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">
                                {{ $related->name }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-2">
                                📍 {{ $related->location }}
                            </p>
                            <p class="text-blue-600 font-bold">
                                Rp {{ number_format($related->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
