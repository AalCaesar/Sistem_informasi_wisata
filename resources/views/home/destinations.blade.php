@extends('layouts.public')

@section('title', 'Jelajahi Destinasi')

@section('content')
<!-- Page Header -->
<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900">Jelajahi Destinasi Wisata</h1>
        <p class="mt-2 text-gray-600">Temukan destinasi wisata terbaik di seluruh Indonesia</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar - Categories Filter -->
        <div class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="font-semibold text-gray-900 mb-4">Kategori</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('public.destinations') }}"
                           class="block px-3 py-2 rounded-md {{ !request('category') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            Semua Kategori
                            <span class="float-right text-sm text-gray-500">{{ $destinations->total() }}</span>
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('public.destinations', ['category' => $category->id]) }}"
                               class="block px-3 py-2 rounded-md {{ request('category') == $category->id ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                {{ $category->name }}
                                <span class="float-right text-sm text-gray-500">{{ $category->destinations_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Main Content - Destinations Grid -->
        <div class="flex-1">
            @if($destinations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($destinations as $destination)
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

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $destinations->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="text-6xl mb-4">🏝️</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Tidak Ada Destinasi
                    </h3>
                    <p class="text-gray-600">
                        @if(request('category'))
                            Belum ada destinasi dalam kategori ini.
                            <a href="{{ route('public.destinations') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Lihat semua destinasi
                            </a>
                        @else
                            Destinasi wisata akan segera ditambahkan. Silakan cek kembali nanti!
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
