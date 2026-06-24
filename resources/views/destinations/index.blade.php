@extends('layouts.admin')

@section('title', 'Daftar Destinasi Wisata')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">📍 Daftar Destinasi Wisata</h1>
    <a href="{{ route('destinations.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
        + Tambah Wisata
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($destinations as $destination)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            @if($destination->image)
                <img src="{{ asset('images/' . $destination->image) }}"
                     alt="{{ $destination->name }}"
                     class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-300 flex items-center justify-center text-gray-600">
                    Tidak Ada Foto
                </div>
            @endif

            <div class="p-4">
                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mb-2">
                    {{ $destination->category->name ?? 'Umum' }}
                </span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $destination->name }}</h3>
                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($destination->description, 100) }}</p>
                <p class="text-blue-600 font-bold text-lg">Rp {{ number_format($destination->price, 0, ',', '.') }}</p>
                <p class="text-gray-500 text-sm mt-1">📍 {{ $destination->location }}</p>
            </div>

            <div class="px-4 pb-4 flex gap-2">
                <a href="{{ route('destinations.show', $destination) }}"
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded text-center transition">
                    Lihat
                </a>
                <a href="{{ route('destinations.edit', $destination) }}"
                   class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-center transition">
                    Edit
                </a>
                <form action="{{ route('destinations.destroy', $destination) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus destinasi ini?')"
                      class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 inline-block">
                <p class="text-yellow-800 font-medium">Belum ada destinasi wisata yang terdaftar.</p>
                <a href="{{ route('destinations.create') }}"
                   class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                    Tambah Destinasi Pertama
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection
