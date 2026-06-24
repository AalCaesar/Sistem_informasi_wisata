<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Destinasi Wisata Daerah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📍 Daftar Destinasi Wisata Daerah</h2>
        <a href="{{ route('destinations.create') }}" class="btn btn-primary">+ Tambah Wisata</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($destinations as $wisata)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($wisata->image)
                        <img src="{{ asset('images/' . $wisata->image) }}" class="card-img-top" alt="{{ $wisata->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">Tidak Ada Foto</div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-info text-dark mb-2">{{ $wisata->category ?? 'Umum' }}</span>
                        <h5 class="card-title">{{ $wisata->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($wisata->description, 100) }}</p>
                        <p class="text-primary fw-bold">Rp {{ number_format($wisata->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('destinations.edit', $wisata->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('destinations.destroy', $wisata->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="alert alert-warning">Belum ada destinasi wisata yang terdaftar.</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>