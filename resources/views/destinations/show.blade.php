@extends('layouts.admin')

@section('title', $destination->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('destinations.index') }}" class="text-blue-600 hover:text-blue-800">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($destination->image)
            <img src="{{ asset('images/' . $destination->image) }}"
                 alt="{{ $destination->name }}"
                 class="w-full h-96 object-cover">
        @else
            <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400 text-lg">No Image Available</span>
            </div>
        @endif

        <div class="p-8">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $destination->name }}</h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $destination->location }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-blue-600">
                        Rp {{ number_format($destination->price, 0, ',', '.') }}
                    </div>
                    <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                        {{ $destination->category->name }}
                    </span>
                </div>
            </div>

            @if($destination->description)
                <div class="mt-6 pt-6 border-t">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Deskripsi</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $destination->description }}</p>
                </div>
            @endif

            <div class="mt-8 pt-6 border-t">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Dibuat:</span>
                        {{ $destination->created_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <span class="font-semibold">Diupdate:</span>
                        {{ $destination->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <a href="{{ route('destinations.edit', $destination) }}"
                   class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg text-center font-semibold">
                    Edit Destinasi
                </a>
                <form action="{{ route('destinations.destroy', $destination) }}"
                      method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Yakin hapus destinasi ini?')"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold">
                        Hapus Destinasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
