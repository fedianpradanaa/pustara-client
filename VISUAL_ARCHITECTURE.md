# Visual Architecture - Open Graph Image System

## 🎯 System Architecture Diagram

```
┌──────────────────────────────────────────────────────────────────────────┐
│                         SOCIAL MEDIA SHARING FLOW                        │
└──────────────────────────────────────────────────────────────────────────┘

User shares homepage URL
        ↓
┌───────────────────────────────────────────────────────────────────────────┐
│ MainController::index()                                                   │
│  └─ Call getSiteInfo()                                                    │
│     ├─ Query tclient (application_name, logo, etc)                       │
│     └─ Query tparameter (og_title, og_description, og_social_image, etc) │
└───────────────────────────────────────────────────────────────────────────┘
        ↓
┌───────────────────────────────────────────────────────────────────────────┐
│ Fallback Logic in MainController                                          │
│                                                                            │
│  $ogSocialImage = $parameters->get('og_social_image')                    │
│        ↓                                                                   │
│  if (empty) → $ogImage = $parameters->get('og_image')                    │
│        ↓                                                                   │
│  if (empty) → $ogImage = asset('.../og-image-default.jpg')              │
└───────────────────────────────────────────────────────────────────────────┘
        ↓
┌───────────────────────────────────────────────────────────────────────────┐
│ Blade Template: mainapp.blade.php                                         │
│                                                                            │
│  <meta property="og:image" content="{{ $site['og_image'] }}" />          │
│  <meta property="og:image:width" content="1200" />                       │
│  <meta property="og:image:height" content="630" />                       │
│  <meta property="og:image:type" content="image/jpeg" />                  │
│  <meta name="twitter:image" content="{{ $site['og_image'] }}" />         │
└───────────────────────────────────────────────────────────────────────────┘
        ↓
┌───────────────────────────────────────────────────────────────────────────┐
│ Social Media Platform (WhatsApp, Facebook, Twitter, LinkedIn, etc)       │
│                                                                            │
│  Parse metadata                                                           │
│  Download og:image (1200×630px)                                          │
│  Display preview with title, description, and image                      │
└───────────────────────────────────────────────────────────────────────────┘
        ↓
User sees beautiful social media preview ✨
```

---

## 📊 Database Schema Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                      TABLE: tclient                         │
├─────────────────────────────────────────────────────────────┤
│ client_id (PK)                                              │
│ application_name                                             │
│ logo                                                         │
│ logo_small                                                   │
│ ... (other fields)                                          │
└─────────────────────────────────────────────────────────────┘
          │
          │ FK: client_id
          ↓
┌─────────────────────────────────────────────────────────────┐
│                      TABLE: tparameter                      │
├─────────────────────────────────────────────────────────────┤
│ client_id (PK/FK)                                           │
│ name (PK) = String (50)                                     │
│ description                                                  │
│ value                                                        │
│ timestamps                                                   │
├─────────────────────────────────────────────────────────────┤
│ PARAMETERS:                                                  │
│ ├─ verify_email_member                                      │
│ ├─ site_tagline                                             │
│ ├─ og_title                                                 │
│ ├─ og_description                                           │
│ ├─ og_image (legacy)                                        │
│ ├─ og_social_image ← NEW! ✨                               │
│ ├─ social_facebook                                          │
│ ├─ social_instagram                                         │
│ ├─ social_youtube                                           │
│ ├─ social_tiktok                                            │
│ └─ social_whatsapp                                          │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎨 Image Fallback Priority Tree

```
                    Get OG Image
                         │
          ┌──────────────┴──────────────┐
          │                             │
    Check og_social_image         Check og_image
       (NEW parameter)           (Legacy parameter)
          │                             │
          ├─ Has value?               ├─ Has value?
          │  Yes ──→ USE IT            │  Yes ──→ USE IT
          │  No ↓                      │  No ↓
          └────────────┬──────────────┘
                        │
                        ↓
            ┌──────────────────────┐
            │  Use Default Image   │
            │  1200×630px, JPEG    │
            │  /storage/images/    │
            │  logo/og-image-      │
            │  default.jpg         │
            └──────────────────────┘
                        │
                        ↓
            ✅ Return URL ke Controller
            
╔════════════════════════════════════════════════════════════╗
║  PENTING:                                                  ║
║  Logo website (.../logo.png) TIDAK LAGI digunakan sebagai │
║  fallback untuk OG image. Hanya untuk navbar/sidebar/login ║
╚════════════════════════════════════════════════════════════╝
```

---

## 🏗️ File Structure

```
pustara-demo/
├── app/
│   └── Http/Controllers/
│       ├── MainController.php ✏️ MODIFIED
│       │   └── getSiteInfo() → Fallback logic
│       └── Core/Setting/
│           └── ParameterController.php ✏️ MODIFIED
│               └── Initialize og_social_image
│
├── database/
│   └── migrations/
│       └── 2026_11_15_000000_add_og_social_image_parameter.php 🆕 NEW
│           └── Add og_social_image to tparameter table
│
├── public/
│   └── storage/images/logo/
│       ├── logo.png (used for navbar/sidebar/login)
│       ├── favicon.png
│       ├── logo_small.png
│       └── og-image-default.jpg 🆕 NEW (1200×630px)
│
├── resources/views/layouts/
│   └── mainapp.blade.php ✏️ MODIFIED
│       └── Add og:image width/height/type metadata
│
└── Documentation 📚
    ├── IMPLEMENTATION_SUMMARY.md 🆕 (Complete overview)
    ├── OPEN_GRAPH_IMAGE_FIX_REPORT.md 🆕 (Technical details)
    ├── PANDUAN_SOCIAL_IMAGE.md 🆕 (Client guide)
    ├── QUICK_REFERENCE.md 🆕 (Quick lookup)
    ├── VISUAL_ARCHITECTURE.md 🆕 (This file)
    ├── test-og-implementation.php 🆕 (Auto tests)
    └── test-og-metadata.sh 🆕 (Shell tests)
```

---

## 🔄 Data Flow Diagram

```
                    ┌─────────────────────┐
                    │  User Visits Site   │
                    │    (Homepage)       │
                    └──────────┬──────────┘
                               │
                               ↓
                    ┌─────────────────────┐
                    │ MainController      │
                    │ @index()            │
                    └──────────┬──────────┘
                               │
                ┌──────────────┼──────────────┐
                │              │              │
                ↓              ↓              ↓
        ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
        │  tclient     │  │  tparameter  │  │  getSiteInfo │
        │  table       │  │  table       │  │  method      │
        └──────┬───────┘  └──────┬───────┘  └──────┬───────┘
               │                 │                  │
               │    Query all    │                  │
               │    parameters   │                  │
               │                 │                  │
               └────────────────┬┴──────────────────┘
                                │
                                ↓
                    ┌─────────────────────┐
                    │  Fallback Logic     │
                    │                     │
                    │  1. og_social_image │
                    │  2. og_image        │
                    │  3. Default image   │
                    └──────────┬──────────┘
                               │
                               ↓
                    ┌─────────────────────┐
                    │  Build $site array  │
                    │  with all metadata  │
                    └──────────┬──────────┘
                               │
                               ↓
                    ┌─────────────────────┐
                    │  Pass to view       │
                    │  (mainapp.blade.php)│
                    └──────────┬──────────┘
                               │
                               ↓
                    ┌─────────────────────┐
                    │  Render HTML        │
                    │  with metadata      │
                    │  og:image           │
                    │  og:image:width     │
                    │  og:image:height    │
                    │  og:image:type      │
                    │  twitter:image      │
                    └──────────┬──────────┘
                               │
                               ↓
                    ┌─────────────────────┐
                    │  Social Platform    │
                    │  (WhatsApp, FB, etc)│
                    │                     │
                    │  Parse metadata &   │
                    │  show preview       │
                    └─────────────────────┘
```

---

## 🧮 Decision Tree - Which Image to Use?

```
                        Need OG Image?
                              │
                    ┌─────────┴─────────┐
                    │                   │
            ┌───────▼──────────┐   ┌────▼──────────┐
            │   Check Config   │   │  Logo Website │
            │   og_social_image│   │  (navbar use) │
            └───────┬──────────┘   └────┬──────────┘
                    │                   │
              ┌─────▼────┐              │
              │  Found?   │              │
              └─────┬────┘              │
                    │                   │
              ┌─────┴──────┐            │
              │             │            │
           YES             NO           │
            │               │            │
      ┌─────▼─┐      ┌──────▼──────┐   │
      │ USE    │      │ Check       │   │
      │ IT     │      │ og_image    │   │
      └────────┘      │ (legacy)    │   │
                      └──────┬──────┘   │
                             │          │
                        ┌────▼───┐      │
                        │ Found? │      │
                        └────┬───┘      │
                             │         │
                      ┌──────┴────┐    │
                      │            │   │
                    YES           NO   │
                     │             │   │
                ┌────▼──┐     ┌────▼─┐ │
                │ USE    │     │ USE  │ │
                │ IT     │     │ DEF  │ │
                └────────┘     │ AUL  │ │
                               │ T    │ │
                               └──────┘ │
                                        │
                    ┌───────────────────┘
                    │
                    ▼
        ┌──────────────────────┐
        │  Render in Metadata  │
        │  og:image            │
        │  twitter:image       │
        └──────────────────────┘
```

---

## 📋 Migration Timeline

```
Timeline: Database Schema Update
┌─────────────────────────────────────────────────────────────┐
│  BEFORE                                                     │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ tparameter                                             │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ client_id | name            | value                   │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ client1   | og_title        | "Perpustakaan A"        │ │
│  │ client1   | og_description  | "Deskripsi"            │ │
│  │ client1   | og_image        | "https://.../image.jpg"│ │
│  │ client1   | social_facebook | "https://facebook.com" │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                           │
                    Migration Run
                           │
                           ↓
┌─────────────────────────────────────────────────────────────┐
│  AFTER                                                      │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ tparameter                                             │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ client_id | name              | value                 │ │
│  ├────────────────────────────────────────────────────────┤ │
│  │ client1   | og_title          | "Perpustakaan A"      │ │
│  │ client1   | og_description    | "Deskripsi"          │ │
│  │ client1   | og_image          | "https://.../..."    │ │
│  │ client1   | og_social_image   | "" ← NEW! ✨          │ │
│  │ client1   | social_facebook   | "https://facebook..." │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                             │
│  Status: ✅ Applied to ALL existing clients                │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔐 Security & Performance Considerations

```
┌──────────────────────────────────────────────────────────┐
│                   SECURITY CHECKS                        │
├──────────────────────────────────────────────────────────┤
│ ✅ URL must be public (no authentication)               │
│ ✅ URL must be HTTPS (recommended)                      │
│ ✅ Image file readable by web server                    │
│ ✅ No sensitive data in image URL                       │
│ ✅ CORS headers not required (metadata only)            │
└──────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────┐
│                PERFORMANCE IMPACT                        │
├──────────────────────────────────────────────────────────┤
│ Query impact: +1 field (og_social_image)               │
│ Cache hit: ✅ Same cache as existing parameters         │
│ Load time: ✅ No significant increase                   │
│ Image download: External (social media crawler)         │
│ Server impact: Minimal (only metadata, not image)       │
└──────────────────────────────────────────────────────────┘
```

---

## 📱 Multi-Client Configuration Example

```
Client A: "Perpustakaan Jakarta"
├─ og_title: "Perpustakaan Digital Jakarta"
├─ og_description: "Akses buku di Jakarta"
├─ og_social_image: "https://jakarta.lib.id/og-image.jpg"
└─ Result: Shows jakarta-specific image in social preview

Client B: "Perpustakaan Bandung"
├─ og_title: "Perpustakaan Digital Bandung"
├─ og_description: "Akses buku di Bandung"
├─ og_social_image: "" (empty - use default)
└─ Result: Shows default image in social preview

Client C: "Perpustakaan Surabaya"
├─ og_title: "Perpustakaan Digital Surabaya"
├─ og_description: "Akses buku di Surabaya"
├─ og_social_image: "https://cdn.surabaya-lib.id/social.jpg"
└─ Result: Shows surabaya-specific image in social preview
```

---

**Version:** 1.0  
**Created:** 15 November 2026  
**Status:** Complete & Tested ✅
