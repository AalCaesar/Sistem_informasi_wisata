# Panduan Step-by-Step Pembuatan Proyek Destinasi Wisata

**Berdasarkan:** WORKFLOW.md  
**Target:** Sistem Manajemen Destinasi Wisata dengan Laravel 13 + Tailwind CSS 4  
**Tech Stack:** PHP 8.3, Laravel 13, MySQL, Tailwind CSS 4, Pest 4

---

## 📋 Daftar Isi
1. [Persiapan Environment](#1-persiapan-environment)
2. [Instalasi Laravel](#2-instalasi-laravel)
3. [Konfigurasi Database](#3-konfigurasi-database)
4. [Membuat Struktur Database](#4-membuat-struktur-database)
5. [Membuat Models](#5-membuat-models)
6. [Membuat Controllers](#6-membuat-controllers)
7. [Konfigurasi Routes](#7-konfigurasi-routes)
8. [Membuat Views](#8-membuat-views)
9. [Setup Authentication](#9-setup-authentication)
10. [Setup Role & Permission](#10-setup-role--permission)
11. [Membuat Seeders](#11-membuat-seeders)
12. [Testing](#12-testing)

---

## 1. Persiapan Environment

### Requirements
```bash
# Cek versi yang dibutuhkan
php -v        # Harus 8.3+
composer -V   # Latest version
node -v       # v18+
npm -v        # v9+
```

### Install Tools (jika belum ada)
- **PHP 8.3**: Download dari [php.net](https://www.php.net/downloads.php)
- **Composer**: [getcomposer.org](https://getcomposer.org/)
- **Node.js**: [nodejs.org](https://nodejs.org/)
- **MySQL/MariaDB**: [mysql.com](https://dev.mysql.com/downloads/)

---

## 2. Instalasi Laravel

### Step 1: Buat Project Baru
```bash
composer create-project laravel/laravel selamet_laravel
cd selamet_laravel
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Generate Application Key
```bash
php artisan key:generate
```

### Step 4: Test Instalasi
```bash
php artisan serve
```
Buka browser: `http://localhost:8000`

---

## 3. Konfigurasi Database

### Step 1: Buat Database
```sql
CREATE DATABASE selamet_wisata CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2: Konfigurasi .env
Edit file `.env`:
```env
APP_NAME="Selamet Wisata"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=selamet_wisata
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3: Test Koneksi
```bash
php artisan migrate
```

---

## 4. Membuat Struktur Database

### Migration 1: Categories Table
```bash
php artisan make:migration create_categories_table
```

Edit `database/migrations/YYYY_MM_DD_create_categories_table.php`:
```php
public function up(): void
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('slug')->unique();
        $table->timestamps();
    });
}
```

### Migration 2: Destinations Table
```bash
php artisan make:migration create_destinations_table
```

Edit `database/migrations/YYYY_MM_DD_create_destinations_table.php`:
```php
public function up(): void
{
    Schema::create('destinations', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->foreignId('category_id')->constrained()->onDelete('restrict');
        $table->string('location');
        $table->string('image')->nullable();
        $table->string('slug')->unique();
        $table->boolean('is_published')->default(true);
        $table->timestamps();
    });
}
```

### Jalankan Migration
```bash
php artisan migrate
```

---

## 5. Membuat Models

### Model Category
```bash
php artisan make:model Category
```

Edit `app/Models/Category.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($category) => $category->slug = Str::slug($category->name));
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class);
    }
}
```

### Model Destination
```bash
php artisan make:model Destination
```

Edit `app/Models/Destination.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Destination extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'category_id',
        'location', 'image', 'slug', 'is_published',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($dest) => $dest->slug = Str::slug($dest->name));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image 
            ? asset('images/' . $this->image)
            : asset('images/placeholder.jpg');
    }
}
```

---

## 6. Membuat Controllers

### DestinationController
```bash
php artisan make:controller DestinationController --resource
```

Edit `app/Http/Controllers/DestinationController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('category')->latest()->get();
        return view('destinations.index', compact('destinations'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($image->getClientOriginalName()) . '.' . $image->extension();
            $image->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        Destination::create($validated);

        return redirect()->route('destinations.index')
            ->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function edit(Destination $destination)
    {
        $categories = Category::orderBy('name')->get();
        return view('destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($destination->image && File::exists(public_path('images/' . $destination->image))) {
                File::delete(public_path('images/' . $destination->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($image->getClientOriginalName()) . '.' . $image->extension();
            $image->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        $destination->update($validated);

        return redirect()->route('destinations.index')
            ->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->image && File::exists(public_path('images/' . $destination->image))) {
            File::delete(public_path('images/' . $destination->image));
        }

        $destination->delete();

        return redirect()->route('destinations.index')
            ->with('success', 'Destinasi berhasil dihapus.');
    }
}
```

---

## 7. Konfigurasi Routes

Edit `routes/web.php`:
```php
<?php

use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('destinations', DestinationController::class);
```

Test routes:
```bash
php artisan route:list --name=destinations
```

---

## 8. Membuat Views

### Layout: app.blade.php (simpel untuk awal)
Buat folder `resources/views/layouts` dan file `app.blade.php`:
```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Selamet Wisata')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <a href="/" class="text-2xl font-bold text-blue-600">Selamet Wisata</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
```

### View: destinations/index.blade.php
Buat folder `resources/views/destinations` dan file `index.blade.php`:
```blade
@extends('layouts.app')

@section('title', 'Daftar Destinasi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Daftar Destinasi</h1>
    <a href="{{ route('destinations.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
        Tambah Destinasi
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($destinations as $destination)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($destination->image)
                <img src="{{ asset('images/' . $destination->image) }}" 
                     alt="{{ $destination->name }}"
                     class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">No Image</span>
                </div>
            @endif
            
            <div class="p-4">
                <h3 class="font-bold text-xl mb-2">{{ $destination->name }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ $destination->location }}</p>
                <p class="text-blue-600 font-bold text-lg mb-3">
                    Rp {{ number_format($destination->price, 0, ',', '.') }}
                </p>
                
                <div class="flex gap-2">
                    <a href="{{ route('destinations.edit', $destination) }}"
                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-center">
                        Edit
                    </a>
                    <form action="{{ route('destinations.destroy', $destination) }}" 
                          method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Yakin hapus destinasi ini?')"
                                class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500 text-lg">Belum ada destinasi. Tambahkan destinasi pertama Anda!</p>
        </div>
    @endforelse
</div>
@endsection
```

### View: destinations/create.blade.php
```blade
@extends('layouts.app')

@section('title', 'Tambah Destinasi')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Destinasi Baru</h1>
    
    <form action="{{ route('destinations.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-lg shadow p-6">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Destinasi *</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="4"
                      class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Harga *</label>
                <input type="number" name="price" value="{{ old('price') }}"
                       class="w-full px-4 py-2 border rounded-lg @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Kategori *</label>
                <select name="category_id"
                        class="w-full px-4 py-2 border rounded-lg @error('category_id') border-red-500 @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Lokasi *</label>
            <input type="text" name="location" value="{{ old('location') }}"
                   class="w-full px-4 py-2 border rounded-lg @error('location') border-red-500 @enderror">
            @error('location')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Gambar (Max 2MB)</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full px-4 py-2 border rounded-lg @error('image') border-red-500 @enderror">
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Simpan
            </button>
            <a href="{{ route('destinations.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
```

### View: destinations/edit.blade.php
```blade
@extends('layouts.app')

@section('title', 'Edit Destinasi')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Destinasi</h1>
    
    <form action="{{ route('destinations.update', $destination) }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nama Destinasi *</label>
            <input type="text" name="name" value="{{ old('name', $destination->name) }}"
                   class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="4"
                      class="w-full px-4 py-2 border rounded-lg">{{ old('description', $destination->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Harga *</label>
                <input type="number" name="price" value="{{ old('price', $destination->price) }}"
                       class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Kategori *</label>
                <select name="category_id" class="w-full px-4 py-2 border rounded-lg">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ $destination->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Lokasi *</label>
            <input type="text" name="location" value="{{ old('location', $destination->location) }}"
                   class="w-full px-4 py-2 border rounded-lg">
        </div>

        @if($destination->image)
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Gambar Saat Ini</label>
                <img src="{{ asset('images/' . $destination->image) }}" 
                     alt="{{ $destination->name }}"
                     class="w-32 h-32 object-cover rounded">
            </div>
        @endif

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Ganti Gambar (Max 2MB)</label>
            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Update
            </button>
            <a href="{{ route('destinations.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
```

### Setup Tailwind CSS
Edit `tailwind.config.js`:
```js
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

Compile assets:
```bash
npm run dev
```

---

## 9. Setup Authentication

### Install Laravel Breeze
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
```

### Test Login
```bash
php artisan serve
```
Buka: `http://localhost:8000/register` untuk register user baru.

---

## 10. Setup Role & Permission

### Install Spatie Permission
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Update User Model
Edit `app/Models/User.php`, tambahkan trait:
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    // ... rest of code
}
```

### Buat Seeder untuk Roles
```bash
php artisan make:seeder RolePermissionSeeder
```

Edit `database/seeders/RolePermissionSeeder.php`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view destinations',
            'create destinations',
            'edit destinations',
            'delete destinations',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $contentManager = Role::create(['name' => 'content_manager']);
        $contentManager->givePermissionTo([
            'view destinations',
            'create destinations',
            'edit destinations',
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo('view destinations');
    }
}
```

---

## 11. Membuat Seeders

### Category Seeder
```bash
php artisan make:seeder CategorySeeder
```

Edit `database/seeders/CategorySeeder.php`:
```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pantai', 'description' => 'Destinasi wisata pantai'],
            ['name' => 'Gunung', 'description' => 'Destinasi wisata gunung'],
            ['name' => 'Budaya', 'description' => 'Destinasi wisata budaya'],
            ['name' => 'Kuliner', 'description' => 'Destinasi wisata kuliner'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
```

### Update DatabaseSeeder
Edit `database/seeders/DatabaseSeeder.php`:
```php
public function run(): void
{
    $this->call([
        RolePermissionSeeder::class,
        CategorySeeder::class,
    ]);
}
```

### Jalankan Seeder
```bash
php artisan db:seed
```

Atau fresh migrate + seed:
```bash
php artisan migrate:fresh --seed
```

---

## 12. Testing

### Buat Test untuk Destination
```bash
php artisan make:test --pest DestinationTest
```

Edit `tests/Feature/DestinationTest.php`:
```php
<?php

use App\Models\Category;
use App\Models\Destination;

test('can view destinations list', function () {
    $response = $this->get('/destinations');
    $response->assertStatus(200);
});

test('can create destination', function () {
    $category = Category::factory()->create();
    
    $data = [
        'name' => 'Test Destination',
        'description' => 'Test Description',
        'price' => 50000,
        'category_id' => $category->id,
        'location' => 'Test Location',
    ];
    
    $response = $this->post('/destinations', $data);
    $response->assertRedirect('/destinations');
    $this->assertDatabaseHas('destinations', ['name' => 'Test Destination']);
});
```

### Jalankan Test
```bash
php artisan test --compact
```

### Format Code dengan Pint
```bash
vendor/bin/pint --format agent
```

---

## ✅ Checklist Completion

- [x] Install Laravel
- [x] Konfigurasi database (.env sudah diatur dengan db_wisata_daerah)
- [x] Buat migrations (categories, destinations) - **1 migration pending: add_location_to_destinations**
- [x] Buat models dengan relationships (Category.php, Destination.php)
- [x] Buat DestinationController dengan CRUD
- [x] Setup routes (web.php sudah diupdate)
- [ ] Buat views (layout, index, create, edit) - **Layout belum dibuat, hanya index & create**
- [ ] Setup Tailwind CSS - **tailwind.config.js belum ada**
- [ ] Install Laravel Breeze (authentication) - **Belum terinstall**
- [ ] Install Spatie Permission (role management) - **Belum terinstall**
- [ ] Buat seeders (roles, categories) - **CategorySeeder ✅, RolePermissionSeeder belum**
- [ ] Buat tests - **Belum ada DestinationTest, hanya ExampleTest**

---

## 🚀 Next Steps

1. **Buat folder untuk images:**
```bash
mkdir public/images
```

2. **Jalankan aplikasi:**
```bash
npm run dev  # Terminal 1
php artisan serve  # Terminal 2
```

3. **Akses aplikasi:**
- Homepage: `http://localhost:8000`
- Destinations: `http://localhost:8000/destinations`

4. **Fitur tambahan yang bisa dikembangkan:**
   - Public homepage untuk user/guest
   - Search & filter destinations
   - Booking system
   - Review & rating
   - Multiple images per destination
   - Map integration (Google Maps/Leaflet)
   - Admin dashboard dengan statistik

---

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs/13.x)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Laravel Breeze](https://laravel.com/docs/13.x/starter-kits#laravel-breeze)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Pest PHP](https://pestphp.com/)

---

**Created:** 25 Juni 2026  
**Version:** 1.0
