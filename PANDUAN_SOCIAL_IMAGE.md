# Panduan: Mengatur Social Sharing Image (Open Graph) - Pustara Client

## 📱 Masalah yang Diperbaiki

Ketika URL website dibagikan di WhatsApp, Facebook, atau Twitter, preview yang muncul sekarang menggunakan **gambar khusus social sharing** (1200×630px) alih-alih logo website.

### Sebelum:
- ❌ Metadata OG menggunakan logo.png
- ❌ Tidak ada dimensi gambar yang benar
- ❌ Metadata tidak lengkap untuk Twitter Card

### Sesudah:
- ✅ Metadata OG menggunakan gambar social sharing khusus
- ✅ Metadata lengkap dengan dimensi 1200×630px
- ✅ Logo website tetap digunakan untuk navbar, sidebar, login
- ✅ Fallback ke gambar default jika belum diatur

---

## 🔧 Cara Mengatur Social Sharing Image

### Langkah 1: Persiapkan Gambar
Siapkan gambar dengan spesifikasi:
- **Dimensi**: 1200 × 630 px (rasio 1.91:1)
- **Format**: JPG atau PNG
- **Ukuran file**: < 500 KB
- **Isi**: Brand/logo library dengan desain landscape

### Langkah 2: Upload ke Server (Opsional)
Jika ingin menyimpan gambar di server Pustara:
1. Upload ke folder: `/public/storage/images/social/`
2. Catat URL lengkapnya: `https://domain.anda/storage/images/social/image.jpg`

Atau gunakan CDN/cloud storage seperti:
- AWS S3
- Google Cloud Storage
- Cloudinary
- ImageKit

### Langkah 3: Masuk Admin Panel
1. Buka: `https://domain.anda/admin` (atau dashboard admin Anda)
2. Login dengan akun admin
3. Cari menu: **Settings** / **Pengaturan**

### Langkah 4: Isi Parameter OG Image

Cari parameter dengan nama: **"Gambar Social Sharing (1200x630px, URL publik)"**

**Opsi A: Gunakan Default Image**
- Biarkan kosong atau skip parameter ini
- Sistem akan menggunakan fallback default image

**Opsi B: Gunakan Custom Image**
- Masukkan URL lengkap gambar Anda:
  ```
  https://pustara.domain.anda/storage/images/social/og-image.jpg
  ```

### Langkah 5: Simpan
- Klik **Save** atau **Update**
- Metadata akan ter-update otomatis di halaman homepage

---

## ✅ Verifikasi Hasil

### Test di WhatsApp
1. Bagikan URL homepage ke WhatsApp (tanpa membuka link)
2. Tunggu preview muncul
3. Pastikan gambar yang tampil adalah social image, bukan logo

### Test di Facebook
1. Kunjungi: https://developers.facebook.com/tools/debug/
2. Paste URL homepage Anda
3. Periksa bagian "Open Graph"

### Test di Twitter
1. Kunjungi: https://cards-dev.twitter.com/validator
2. Paste URL homepage Anda
3. Lihat preview gambar

### Via Command Line (Linux/Mac)
```bash
curl -s https://domain.anda/ | grep -A 2 "og:image"
```

Hasil yang diharapkan:
```html
<meta property="og:image" content="https://domain.anda/.../image.jpg" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
```

---

## 📝 Metadata yang Diatur

Sistem sekarang menghasilkan metadata lengkap seperti ini:

```html
<!-- Open Graph untuk Facebook, LinkedIn, dll -->
<meta property="og:type" content="website">
<meta property="og:title" content="Nama Library Anda">
<meta property="og:description" content="Deskripsi library Anda">
<meta property="og:url" content="https://domain.anda/">
<meta property="og:image" content="https://domain.anda/.../image.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/jpeg">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Nama Library Anda">
<meta name="twitter:description" content="Deskripsi library Anda">
<meta name="twitter:image" content="https://domain.anda/.../image.jpg">
```

---

## 🎯 Parameter Konfigurasi

| Parameter | Deskripsi | Wajib? | Contoh |
|-----------|-----------|--------|--------|
| `og_title` | Judul untuk sharing | Opsional | "Perpustakaan Digital Indonesia" |
| `og_description` | Deskripsi untuk sharing | Opsional | "Akses ribuan buku digital gratis" |
| `og_image` | URL gambar OG (legacy) | Opsional | https://.../ |
| `og_social_image` | **URL gambar social sharing** | **Opsional** | **https://.../og-image.jpg** |
| `site_tagline` | Tagline perpustakaan | Opsional | "Membaca Digital Menjadi Mudah" |

### Fallback Priority:
1️⃣ `og_social_image` (parameter baru, khusus untuk social)
2️⃣ `og_image` (parameter lama, untuk backward compatibility)
3️⃣ Default image → `/storage/images/logo/og-image-default.jpg` (1200×630px)

---

## ⚠️ Hal Penting yang Perlu Diketahui

### Logo Website Tidak Berubah
- ✅ Logo tetap digunakan untuk navbar, sidebar, login
- ✅ Favicon tetap bekerja normal
- ✅ Small logo tetap tersimpan
- ❌ Logo TIDAK lagi digunakan sebagai OG image

### URL Gambar Harus Publik
- ✅ URL harus bisa diakses tanpa login
- ✅ Harus menggunakan protocol https://
- ✅ Harus akses publik tanpa authentication
- ❌ Jangan gunakan URL lokal (localhost, 192.168.x.x)

### Dimensi Gambar Penting
- ✅ Rekomendasi: **1200 × 630 px** (rasio 1.91:1)
- ✅ Minimum: 600 × 314 px
- ⚠️ Gambar yang terlalu kecil tidak akan jelas di social media
- ⚠️ Gambar landscape (horizontal) lebih baik daripada portrait

### Format Gambar
- ✅ Format terbaik: **JPEG** (quality 80-85%)
- ✅ Alternatif: PNG (jika perlu transparan)
- ⚠️ Ukuran file: sebaiknya < 500 KB
- ⚠️ GIF tidak direkomendasikan untuk OG

---

## 🎨 Contoh Desain Social Image

### Opsi 1: Logo + Tagline
```
┌─────────────────────────────────────────┐
│                                         │
│    [Logo Library]  "Baca Digital"       │
│                                         │
│  Akses ribuan buku dari rumah Anda      │
│                                         │
└─────────────────────────────────────────┘
```

### Opsi 2: Hero Image dengan Text Overlay
```
┌─────────────────────────────────────────┐
│     [Hero Background Image]             │
│                                         │
│            NAMA LIBRARY                 │
│     Perpustakaan Digital Terpercaya     │
│                                         │
└─────────────────────────────────────────┘
```

### Opsi 3: Clean Branding
```
┌─────────────────────────────────────────┐
│                                         │
│      [Logo Besar]                       │
│                                         │
│  www.domain.anda                        │
│                                         │
└─────────────────────────────────────────┘
```

### Tools untuk Membuat:
- **Canva**: https://www.canva.com (template 1200x630)
- **Figma**: https://www.figma.com
- **Photoshop**: Crop ke 1200x630
- **GIMP** (gratis): Resize ke 1200x630
- **Online Editor**: https://www.pixlr.com/

---

## 🐛 Troubleshooting

### Q: Gambar tidak muncul di WhatsApp preview?
A: 
1. Pastikan URL gambar bisa diakses (tidak lokal)
2. Refresh WhatsApp cache: clear app data & reload
3. Tunggu 24 jam untuk update cache social media

### Q: Gambar muncul tapi kualitas jelek?
A:
1. Pastikan dimensi 1200×630 px
2. Tingkatkan kualitas file (resize dengan GIMP/Photoshop)
3. Gunakan format JPEG dengan quality 85%

### Q: URL gambar error 404?
A:
1. Pastikan file ada di server
2. Check URL dengan copy-paste di browser
3. Periksa path dan permissions file

### Q: Ingin balik ke default image?
A:
1. Buka admin panel Settings
2. Hapus URL di parameter `og_social_image`
3. Biarkan kosong → sistem akan pakai default

### Q: Gambar masih belum update di Facebook?
A:
1. Clear cache Facebook: https://developers.facebook.com/tools/debug/
2. Paste URL lagi
3. Klik "Scrape Again"

---

## 📞 Support

Jika ada masalah atau pertanyaan:
- Hubungi admin Pustara Client
- Email: support@pustara.id
- Chat: Gunakan help desk Anda

---

**Terakhir diupdate:** 15 November 2026
**Versi:** 1.0
**Status:** Production Ready
