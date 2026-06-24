# Workflow Sistem Manajemen Destinasi Wisata

## Daftar Isi
- [Overview](#overview)
- [Role dan Akses](#role-dan-akses)
- [Workflow Administrator](#workflow-administrator)
- [Workflow Content Manager](#workflow-content-manager)
- [Workflow User/Guest](#workflow-userguest)

---

## Overview

Dokumen ini menjelaskan alur kerja (workflow) untuk setiap role dalam sistem manajemen destinasi wisata. Sistem ini memungkinkan pengelolaan destinasi wisata, kategori, dan informasi terkait lainnya.

### Struktur Role
1. **Administrator** - Akses penuh ke seluruh sistem
2. **Content Manager** - Mengelola konten destinasi dan kategori
3. **User/Guest** - Melihat dan mencari destinasi wisata

---

## Role dan Akses

| Fitur | Administrator | Content Manager | User/Guest |
|-------|--------------|----------------|------------|
| Dashboard | ✅ Full Access | ✅ Limited | ❌ |
| Kelola Destinasi | ✅ CRUD | ✅ CRUD | ❌ |
| Kelola Kategori | ✅ CRUD | ✅ Create, Read | ❌ |
| Upload Gambar | ✅ | ✅ | ❌ |
| Lihat Destinasi | ✅ | ✅ | ✅ |
| Filter/Search | ✅ | ✅ | ✅ |
| User Management | ✅ | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ |

---

## Workflow Administrator

### 1. Login & Dashboard
```
┌─────────────┐
│ Login Page  │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│ Admin Dashboard     │
│ - Statistics        │
│ - Quick Actions     │
│ - Recent Activities │
└─────────────────────┘
```

**Langkah:**
1. Akses halaman login
2. Masukkan kredensial admin
3. Redirect ke dashboard admin
4. Lihat overview sistem (total destinasi, kategori, user)

---

### 2. Mengelola Destinasi

#### A. Menambah Destinasi Baru
```
Dashboard → Destinasi → Tambah Baru
```

**Langkah:**
1. Klik menu "Destinasi" di sidebar
2. Klik tombol "Tambah Destinasi"
3. Isi form:
   - Nama destinasi (required)
   - Deskripsi (optional)
   - Harga (required)
   - Kategori (required, pilih dari dropdown)
   - Lokasi (required)
   - Upload gambar (optional, max 2MB)
4. Klik "Simpan"
5. Sistem validasi data
6. Redirect ke daftar destinasi dengan pesan sukses

**Validasi:**
- Nama: wajib diisi, max 255 karakter
- Harga: wajib diisi, numeric, min 0
- Kategori: wajib dipilih, harus ada di database
- Lokasi: wajib diisi, max 255 karakter
- Gambar: optional, format image, max 2048KB

**File terkait:**
- Controller: `app/Http/Controllers/DestinationController.php:36`
- View: `resources/views/destinations/create.blade.php`
- Route: `routes/web.php` (POST /destinations)

---

#### B. Edit Destinasi
```
Dashboard → Destinasi → [Pilih Destinasi] → Edit
```

**Langkah:**
1. Dari daftar destinasi, klik tombol "Edit" pada destinasi yang ingin diubah
2. Form edit akan terisi dengan data existing
3. Ubah data yang diperlukan
4. Upload gambar baru (optional)
   - Jika upload gambar baru, gambar lama akan dihapus otomatis
5. Klik "Update"
6. Sistem validasi data
7. Redirect ke daftar destinasi dengan pesan sukses

**File terkait:**
- Controller: `app/Http/Controllers/DestinationController.php:79`
- View: `resources/views/destinations/edit.blade.php`

---

#### C. Hapus Destinasi
```
Dashboard → Destinasi → [Pilih Destinasi] → Hapus
```

**Langkah:**
1. Dari daftar destinasi, klik tombol "Hapus"
2. Konfirmasi penghapusan (best practice: tambahkan modal konfirmasi)
3. Sistem akan:
   - Hapus file gambar dari storage jika ada
   - Hapus data destinasi dari database
4. Redirect ke daftar destinasi dengan pesan sukses

**File terkait:**
- Controller: `app/Http/Controllers/DestinationController.php:110`

---

### 3. Mengelola Kategori

#### A. Menambah Kategori
```
Dashboard → Kategori → Tambah Baru
```

**Langkah:**
1. Klik menu "Kategori"
2. Klik tombol "Tambah Kategori"
3. Isi nama kategori
4. Isi deskripsi (optional)
5. Klik "Simpan"

---

#### B. Edit/Hapus Kategori
```
Dashboard → Kategori → [Pilih Kategori] → Edit/Hapus
```

**Catatan:** Admin harus berhati-hati menghapus kategori yang masih digunakan oleh destinasi.

---

### 4. User Management

**Workflow:**
```
Dashboard → Users → [Manage Users]
```

**Kemampuan:**
- Tambah user baru (Admin, Content Manager, User)
- Edit role user
- Nonaktifkan/Aktifkan user
- Reset password user
- Lihat activity log user

---

### 5. System Settings

**Workflow:**
```
Dashboard → Settings
```

**Pengaturan yang dapat dikelola:**
- General settings (nama aplikasi, logo, dll)
- Email configuration
- File upload settings (max size, allowed types)
- Backup & maintenance

---

## Workflow Content Manager

### 1. Login & Dashboard
```
┌─────────────┐
│ Login Page  │
└──────┬──────┘
       │
       ▼
┌──────────────────────┐
│ Content Dashboard    │
│ - My Recent Content  │
│ - Pending Reviews    │
│ - Quick Actions      │
└──────────────────────┘
```

**Akses:**
- Login dengan role "Content Manager"
- Dashboard terbatas (hanya konten-related)

---

### 2. Mengelola Destinasi

Content Manager memiliki workflow yang sama dengan Administrator untuk mengelola destinasi:

#### A. Menambah Destinasi
```
Dashboard → Destinasi → Tambah Baru
```
(Sama seperti workflow Administrator bagian 2.A)

#### B. Edit Destinasi
```
Dashboard → Destinasi → Edit
```
(Sama seperti workflow Administrator bagian 2.B)

#### C. Hapus Destinasi
```
Dashboard → Destinasi → Hapus
```
(Sama seperti workflow Administrator bagian 2.C)

**Perbedaan:**
- Content Manager mungkin perlu approval untuk perubahan tertentu
- Tidak bisa mengubah status publish (jika fitur ini ditambahkan)

---

### 3. Mengelola Kategori (Terbatas)

#### A. Menambah Kategori
```
Dashboard → Kategori → Tambah Baru
```

Content Manager dapat membuat kategori baru, namun mungkin perlu approval dari Administrator.

#### B. Melihat Kategori
Content Manager dapat melihat semua kategori untuk keperluan assignment ke destinasi.

**Keterbatasan:**
- Tidak dapat menghapus kategori
- Tidak dapat edit kategori yang sudah ada (opsional, tergantung kebijakan)

---

### 4. Upload & Kelola Media

**Workflow:**
```
Dashboard → Media Library → Upload
```

**Langkah:**
1. Akses Media Library
2. Upload gambar destinasi
3. Organize dalam folder/kategori
4. Gunakan saat membuat/edit destinasi

**Validasi:**
- Format: JPG, PNG, WEBP
- Max size: 2MB per file
- Dimensi recommended: min 1200x800px

---

## Workflow User/Guest

### 1. Akses Homepage
```
┌──────────────┐
│ Homepage     │
│ - Hero       │
│ - Featured   │
│ - Categories │
└──────────────┘
```

**Tanpa login, user dapat:**
- Melihat homepage
- Browse destinasi wisata
- Filter berdasarkan kategori
- Search destinasi

---

### 2. Browse Destinasi

**Workflow:**
```
Homepage → Daftar Destinasi → Detail Destinasi
```

**Langkah:**
1. Klik "Lihat Semua Destinasi" atau kategori tertentu
2. Lihat grid/list destinasi dengan:
   - Gambar
   - Nama
   - Harga
   - Lokasi
   - Kategori
3. Klik destinasi untuk melihat detail

---

### 3. Filter & Search

#### A. Filter berdasarkan Kategori
```
Destinasi → [Pilih Kategori]
```

**Langkah:**
1. Klik filter kategori (Pantai, Gunung, Budaya, dll)
2. Sistem tampilkan destinasi sesuai kategori
3. User dapat memilih multiple kategori (opsional)

---

#### B. Search Destinasi
```
[Search Bar] → Input keyword → Enter
```

**Langkah:**
1. Ketik nama destinasi atau lokasi di search bar
2. Sistem search berdasarkan:
   - Nama destinasi
   - Lokasi
   - Deskripsi
3. Tampilkan hasil search

---

### 4. Lihat Detail Destinasi

**Workflow:**
```
Daftar Destinasi → [Klik Destinasi] → Detail Page
```

**Informasi yang ditampilkan:**
- Nama destinasi
- Gambar (full size)
- Deskripsi lengkap
- Harga
- Lokasi
- Kategori
- Map/lokasi (jika ada integrasi)
- Galeri foto (jika ada)
- Review/rating (jika fitur ditambahkan)

---

### 5. Booking/Pemesanan (Future Feature)

**Workflow untuk fitur booking:**
```
Detail Destinasi → Pilih Tanggal → Isi Form → Payment → Konfirmasi
```

**Langkah (jika fitur ditambahkan):**
1. User harus login/register
2. Pilih tanggal kunjungan
3. Pilih jumlah tiket
4. Isi data pengunjung
5. Pilih metode pembayaran
6. Konfirmasi booking
7. Terima email konfirmasi

---

## Flow Diagram Utama

### 1. Destination Management Flow
```
┌──────────────────────────────────────────────────────┐
│                   DESTINATION CRUD                    │
└──────────────────────────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
        ▼                  ▼                  ▼
   ┌────────┐        ┌────────┐        ┌────────┐
   │ Create │        │ Update │        │ Delete │
   └────┬───┘        └────┬───┘        └────┬───┘
        │                 │                  │
        ▼                 ▼                  ▼
   ┌─────────────────────────────────────────────┐
   │         Validation & Authorization          │
   └─────────────────────────────────────────────┘
        │                 │                  │
        ▼                 ▼                  ▼
   ┌──────────┐     ┌──────────┐     ┌──────────┐
   │  Save    │     │  Update  │     │  Remove  │
   │  Image   │     │  Image   │     │  Image   │
   └──────────┘     └──────────┘     └──────────┘
        │                 │                  │
        └─────────────────┴──────────────────┘
                          │
                          ▼
                 ┌─────────────────┐
                 │ Redirect + Flash│
                 └─────────────────┘
```

---

### 2. User Access Flow
```
┌─────────────┐
│   Request   │
└──────┬──────┘
       │
       ▼
┌──────────────┐      No      ┌──────────────┐
│ Authenticated?├─────────────►│ Public Pages │
└──────┬───────┘              └──────────────┘
       │ Yes
       ▼
┌──────────────┐
│  Check Role  │
└──────┬───────┘
       │
   ┌───┴────┬────────────┬──────────┐
   │        │            │          │
   ▼        ▼            ▼          ▼
┌─────┐ ┌────────┐ ┌─────────┐ ┌──────┐
│Admin│ │Content │ │  User   │ │Guest │
│     │ │Manager │ │         │ │      │
└──┬──┘ └───┬────┘ └────┬────┘ └───┬──┘
   │        │           │          │
   └────────┴───────────┴──────────┘
                │
                ▼
        ┌──────────────┐
        │ Authorized   │
        │ Actions      │
        └──────────────┘
```

---

## Error Handling

### Validation Errors
- Display inline pada form
- Highlight field yang error
- Tampilkan pesan error yang jelas
- Keep user input (old values)

### Server Errors
- Log error ke file/system
- Display user-friendly error message
- Tidak expose technical details ke user
- Provide action untuk retry/contact support

### File Upload Errors
- File size exceeded
- Invalid file type
- Upload failure
- Automatic cleanup on error

---

## Best Practices

### Untuk Administrator:
1. ✅ Backup data secara berkala
2. ✅ Monitor user activities
3. ✅ Review content sebelum publish
4. ✅ Maintain kategori yang konsisten
5. ✅ Optimize gambar sebelum upload

### Untuk Content Manager:
1. ✅ Gunakan nama destinasi yang SEO-friendly
2. ✅ Tulis deskripsi yang informatif dan menarik
3. ✅ Upload gambar berkualitas tinggi
4. ✅ Assign kategori yang tepat
5. ✅ Update informasi secara berkala

### Untuk Developer:
1. ✅ Implement authorization middleware
2. ✅ Add comprehensive validation
3. ✅ Optimize database queries
4. ✅ Implement caching where needed
5. ✅ Add proper error logging

---

## Rekomendasi Pengembangan

### 1. Authentication & Authorization
- Implement Laravel Breeze/Jetstream untuk auth
- Gunakan Spatie Permission untuk role management
- Add middleware untuk protect routes

### 2. User Experience
- Add loading states
- Implement toast notifications
- Add image preview before upload
- Progressive image loading

### 3. Features Enhancement
- Booking system
- Review & rating
- Wishlist/favorites
- Social sharing
- Map integration
- Multiple images per destination
- Image gallery

### 4. Performance
- Image optimization (WebP format)
- Lazy loading
- Database indexing
- Query optimization
- Caching (Redis/Memcached)

### 5. Security
- CSRF protection (already included in Laravel)
- Rate limiting
- Input sanitization
- Secure file upload
- Regular security audits

---

## Kontak & Support

Untuk pertanyaan atau issue terkait workflow ini, silakan hubungi:
- **Developer Team**: [email]
- **Documentation**: [link to docs]
- **Issue Tracker**: [link to issue tracker]

---

**Versi:** 1.0  
**Last Updated:** 25 Juni 2026  
**Maintained by:** Development Team
