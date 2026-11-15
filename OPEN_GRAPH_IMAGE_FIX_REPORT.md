# LAPORAN PERBAIKAN OPEN GRAPH IMAGE - PUSTARA CLIENT

## 1. RINGKASAN MASALAH

Ketika URL website dibagikan melalui WhatsApp, preview menggunakan **logo.png** sebagai thumbnail, padahal gambar khusus social sharing (1200×630px) sudah disiapkan.

### Masalah Teknis:
- Metadata `og:image` fallback ke `logoUrl` jika parameter kosong
- Tidak ada mekanisme terpisah untuk social sharing image
- Tidak ada informasi dimensi gambar (width/height) di metadata
- Metadata tidak lengkap untuk Twitter Card

---

## 2. PERUBAHAN YANG DILAKUKAN

### A. Database Migration
📄 **File**: `database/migrations/2026_11_15_000000_add_og_social_image_parameter.php`

**Apa yang dilakukan:**
- Tambah parameter baru `og_social_image` untuk setiap client
- Description: "Gambar Social Sharing (1200x630px, URL publik)"
- Default value: '' (kosong - akan menggunakan fallback ke default image)
- Status: ✅ Migration sudah dijalankan

### B. Controller Update
📄 **File**: `app/Http/Controllers/MainController.php`

**Perubahan di `getSiteInfo()` method:**

1. **Tambah parameter `og_social_image` ke dalam query** (line 705-707)
   ```php
   ->whereIn('name', [
       // ... existing parameters ...
       'og_social_image',  // ← BARU
       // ...
   ])
   ```

2. **Pisahkan logika logo dan social image** (line 727-730)
   ```php
   // Sebelum:
   $ogImage = trim((string) $parameters->get('og_image', '')) ?: $logoUrl;
   
   // Sesudah:
   $ogSocialImage = trim((string) $parameters->get('og_social_image', ''));
   $ogImage = $ogSocialImage 
       ?: (trim((string) $parameters->get('og_image', '')) ?: asset('storage/images/logo/og-image-default.jpg'));
   ```

**Logika baru:**
1. Cek parameter `og_social_image` (khusus social sharing)
2. Jika kosong, cek parameter `og_image` (legacy/backup)
3. Jika masih kosong, gunakan default image `/storage/images/logo/og-image-default.jpg`
4. ❌ TIDAK lagi menggunakan `logoUrl` sebagai fallback

### C. ParameterController Update
📄 **File**: `app/Http/Controllers/Core/Setting/ParameterController.php`

**Perubahan di `index()` method (line 63-64):**
- Tambah parameter `og_social_image` ke dalam `$clientParameters` array
- Description: "Gambar Social Sharing (1200x630px, URL publik)"
- Ini memastikan parameter auto-create untuk client baru

### D. Blade Template Update
📄 **File**: `resources/views/layouts/mainapp.blade.php`

**Perubahan metadata Open Graph (line 7-25):**

Sebelum:
```html
<meta property="og:image" itemprop="image" content="{{ $site['og_image'] }}" />
```

Sesudah:
```html
<meta property="og:image" itemprop="image" content="{{ $site['og_image'] }}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:type" content="image/jpeg" />
```

**Metadata Twitter Card juga diupdate dengan gambar yang sama.**

### E. Default OG Image
📄 **File**: `public/storage/images/logo/og-image-default.jpg` (BARU)

**Karakteristik:**
- Dimensi: 1200 × 630 px (rasio 1.91:1, sesuai standar OG)
- Format: JPEG (quality 85%)
- Desain: Gradient biru profesional dengan border putih
- Ukuran file: ~27.7 KB
- Diakses publik tanpa autentikasi

---

## 3. ALUR FALLBACK IMAGE

```
┌─────────────────────────────────────────────────────────────┐
│ Pengambilan OG Image untuk Metadata                         │
└─────────────────────────────────────────────────────────────┘

1. Cek parameter tparameter.og_social_image (khusus social)
   ├─ Jika ada → Gunakan URL tersebut
   └─ Jika kosong → Lanjut ke step 2

2. Cek parameter tparameter.og_image (legacy/backup)
   ├─ Jika ada → Gunakan URL tersebut
   └─ Jika kosong → Lanjut ke step 3

3. Gunakan default image
   └─ URL: /storage/images/logo/og-image-default.jpg
   └─ Dimensi: 1200 × 630 px

┌─────────────────────────────────────────────────────────────┐
│ PENTING: Logo website TIDAK lagi digunakan sebagai fallback │
└─────────────────────────────────────────────────────────────┘
```

---

## 4. MULTI-CLIENT SUPPORT

Setiap client memiliki konfigurasi terpisah:

```
Database (tparameter table):
├─ client_id: "client-a"
│  ├─ og_social_image: https://pustara-a.id/social-image.jpg
│  └─ og_title: "Perpustakaan A"
│
└─ client_id: "client-b"
   ├─ og_social_image: https://pustara-b.id/images/og.jpg
   └─ og_title: "Perpustakaan B"
```

Setiap client dapat:
- ✅ Mengatur social image berbeda dari logo
- ✅ Menggunakan default image (fallback)
- ✅ Menggunakan URL publik dari domain manapun
- ✅ Update tanpa restart aplikasi

---

## 5. FITUR YANG TETAP DIPERTAHANKAN

✅ Logo website tetap berfungsi normal
✅ Metadata `og:title` dan `og:description`
✅ Favicon dan small logo
✅ Social media links (Facebook, Instagram, YouTube, TikTok, WhatsApp)
✅ Sistem multi-client tetap stabil
✅ Konfigurasi legacy `og_image` masih support sebagai backup

---

## 6. CARA CLIENT MENGATUR SOCIAL SHARING IMAGE

### Langkah 1: Upload gambar ke server (optional)
Client bisa mengupload gambar khusus ke:
- Folder: `/public/storage/images/social/` (jika ingin terpisah dari logo)
- Atau: Gunakan URL publik dari CDN/cloud storage

### Langkah 2: Masuk ke Admin Panel
- Login sebagai admin
- Pergi ke: **Settings / Pengaturan**
- Cari parameter: **Gambar Social Sharing (1200x630px, URL publik)**

### Langkah 3: Masukkan URL Gambar
- Format: URL absolut (https://...)
- Contoh: `https://pustara.pustakanusantara.id/storage/images/social/og-image.jpg`
- Atau kosongkan untuk menggunakan default image

### Langkah 4: Simpan
- Klik Save
- Metadata akan otomatis ter-update di halaman

---

## 7. PENGUJIAN

### Test 1: Verifikasi Metadata di HTML
```bash
curl -s https://pustara.pustakanusantara.id/ | grep -A 2 'og:image'
```

**Output yang diharapkan:**
```html
<meta property="og:image" itemprop="image" content="https://pustara.pustakanusantara.id/storage/images/logo/og-image-default.jpg" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:type" content="image/jpeg" />
<meta name="twitter:image" content="https://pustara.pustakanusantara.id/storage/images/logo/og-image-default.jpg">
```

### Test 2: Verifikasi Dimensi Gambar
```bash
# Check default OG image dimensions
identify public/storage/images/logo/og-image-default.jpg

# Output yang diharapkan:
# og-image-default.jpg JPEG 1200x630 1200x630+0+0 8-bit DirectClass 27.7KB
```

### Test 3: Test di WhatsApp
1. Bagikan URL homepage ke WhatsApp
2. Verifikasi preview:
   - Gambar yang tampil adalah OG image (1200×630), bukan logo
   - Judul dan deskripsi dari parameter `og_title` dan `og_description`

### Test 4: Cek dengan Online Tools
- Facebook Debugger: https://developers.facebook.com/tools/debug/
- Twitter Card Validator: https://cards-dev.twitter.com/validator
- OpenGraph Preview: https://www.opengraph.xyz/

---

## 8. FILE YANG DIUBAH

| File | Jenis | Status |
|------|-------|--------|
| `database/migrations/2026_11_15_000000_add_og_social_image_parameter.php` | Baru | ✅ Migrated |
| `app/Http/Controllers/MainController.php` | Update | ✅ Modified |
| `app/Http/Controllers/Core/Setting/ParameterController.php` | Update | ✅ Modified |
| `resources/views/layouts/mainapp.blade.php` | Update | ✅ Modified |
| `public/storage/images/logo/og-image-default.jpg` | Baru | ✅ Created |

---

## 9. KOMPATIBILITAS

| Aspek | Status | Catatan |
|-------|--------|--------|
| Logo website | ✅ Tetap normal | Tidak menggunakan logo sebagai OG |
| Multi-client | ✅ Fully supported | Setiap client config terpisah |
| Legacy `og_image` | ✅ Backward compatible | Fallback jika `og_social_image` kosong |
| Existing metadata | ✅ Preserved | Semua parameter lama tetap ada |
| Performance | ✅ No impact | Hanya tambah 1 query field |
| Database | ✅ Safe | Migration reversible dengan `rollback` |

---

## 10. ROLLBACK (Jika Diperlukan)

Jika ingin kembali ke versi lama:

```bash
# Revert migration
php artisan migrate:rollback --step=1

# Revert code changes (gunakan git)
git checkout app/Http/Controllers/MainController.php
git checkout app/Http/Controllers/Core/Setting/ParameterController.php
git checkout resources/views/layouts/mainapp.blade.php
```

---

## 11. CATATAN PENTING

⚠️ **PERHATIAN:**

1. **Logo tetap digunakan untuk:**
   - Navbar, sidebar, login page
   - Branding internal aplikasi
   - Favicon

2. **OG Image HANYA digunakan untuk:**
   - Social media preview (WhatsApp, Facebook, Twitter)
   - Link sharing di platform sosial

3. **Dimensi OG Image:**
   - Rekomendasi: 1200 × 630 px (rasio 1.91:1)
   - Minimum: 600 × 314 px
   - Optimal quality: JPEG dengan kompresi 80-85%

4. **URL gambar harus:**
   - Absolut (dengan https://)
   - Publik (tidak perlu login untuk akses)
   - CORS-friendly (jika dari domain berbeda)
   - Accessible (bukan local path)

---

## 12. CHANGELOG

**Version 1.0 (2026-11-15)**
- ✅ Separasi logo dan social sharing image
- ✅ Tambah parameter `og_social_image` ke database
- ✅ Update MainController logic untuk fallback yang benar
- ✅ Lengkapi metadata OG dengan width/height/type
- ✅ Buat default OG image (1200×630px)
- ✅ Update ParameterController untuk auto-create parameter
- ✅ Backward compatible dengan `og_image` lama

---

**Created**: 2026-11-15
**Status**: Ready for Production
**Tested**: ✅ Migration, ✅ File creation, ✅ Code changes verified
