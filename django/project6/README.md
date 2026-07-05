<div align="center">

# ✍️ Blog Wahyu

**Platform blog modern bergaya _Threads_ — dengan login berlapis Email + OTP, dibangun menggunakan Django 6.**

![Python](https://img.shields.io/badge/Python-3.13-3776AB?style=for-the-badge&logo=python&logoColor=white)
![Django](https://img.shields.io/badge/Django-6.0.4-092E20?style=for-the-badge&logo=django&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-07405E?style=for-the-badge&logo=sqlite&logoColor=white)
![Tests](https://img.shields.io/badge/tests-26%20passed-2ea44f?style=for-the-badge)
![Status](https://img.shields.io/badge/status-selesai-brightgreen?style=for-the-badge)

[Fitur](#-fitur-utama) · [Instalasi](#-instalasi--menjalankan) · [Login OTP](#-login-email--otp) · [Akun Contoh](#-akun-contoh) · [Struktur](#️-struktur-proyek) · [Pengujian](#-pengujian)

</div>

---

## 📖 Tentang Proyek

**Blog Wahyu** adalah aplikasi blog lengkap yang menjadi puncak (proyek ke-6) dari rangkaian
pembelajaran Django `project-1` hingga `project-5`. Aplikasi ini menggabungkan seluruh konsep
sebelumnya lalu mengembangkannya menjadi sebuah produk nyata: **CRUD penuh**, **autentikasi
berlapis dengan OTP email**, **sistem komentar**, hingga **antarmuka gelap bergaya Threads**.

> 🎓 Dibuat untuk mata kuliah **Pengembangan Aplikasi Web (Framework)** — Semester 6.

---

## ✨ Fitur Utama

### 📝 Konten & Blog
- **CRUD postingan** penuh — buat, baca, sunting, hapus (Class-Based Views)
- **Status Draft / Dipublikasikan** — draft hanya terlihat oleh penulisnya
- **Kategori** postingan lengkap dengan filter per kategori
- **Pencarian** berdasarkan judul & isi
- **Paginasi** otomatis pada feed
- **Unggah gambar** utama postingan (media + Pillow)
- **Komentar** antar pengguna (khusus yang sudah masuk)

### 🔐 Autentikasi & Keamanan
- **Login 2 langkah** → Email + Password, lalu verifikasi **OTP** yang dikirim ke email
- **Pendaftaran** dengan email wajib & unik
- **Kode OTP** 6 digit, berlaku 5 menit, sekali pakai, batas percobaan & kirim ulang
- **Otorisasi** — hanya penulis yang boleh menyunting/menghapus postingannya

### 🎨 Antarmuka
- **Tema bergaya Threads** — feed satu kolom, avatar berwarna, _action bar_
- **Mode Gelap / Terang** dengan tombol pengalih (tersimpan otomatis)
- **Responsif** untuk desktop maupun ponsel

### 🛠️ Developer Experience
- **Panel Admin kustom** — filter, pencarian, slug otomatis, inline komentar, aksi setujui
- **26 unit test** mencakup model, view, auth, & alur OTP
- **Management command** `seed_data` untuk mengisi data contoh instan

---

## 🖼️ Tampilan

> 💡 Untuk melengkapi README, letakkan tangkapan layar ke folder `docs/` lalu aktifkan blok di bawah.

<!--
| Beranda (Feed) | Detail & Komentar | Login + OTP |
|:---:|:---:|:---:|
| ![Feed](docs/feed.png) | ![Detail](docs/detail.png) | ![OTP](docs/otp.png) |
-->

---

## 🧰 Teknologi

| Kategori | Teknologi |
|----------|-----------|
| **Bahasa** | Python 3.13 |
| **Framework** | Django 6.0.4 |
| **Database** | SQLite 3 |
| **Media** | Pillow |
| **Konfigurasi** | python-dotenv (`.env`) |
| **Email/OTP** | Django Email (SMTP / konsol) |
| **Formatter** | Black |

---

## 🚀 Instalasi & Menjalankan

Pastikan **Python 3.13+** sudah terpasang, lalu jalankan langkah berikut dari folder `project6/`.

```bash
# 1. Buat & aktifkan virtual environment
python -m venv .venv
.venv\Scripts\activate           # Windows (PowerShell/CMD)
# source .venv/bin/activate      # Linux / macOS

# 2. Pasang dependency
pip install -r requirements.txt

# 3. Siapkan database
python manage.py migrate

# 4. Isi data contoh (kategori, postingan, akun admin & user)
python manage.py seed_data

# 5. Jalankan server pengembangan
python manage.py runserver
```

🌐 Buka **http://127.0.0.1:8000/**

<details>
<summary>📧 (Opsional) Aktifkan pengiriman email OTP asli</summary>

Salin `.env.example` menjadi `.env`, lalu isi kredensial email Anda:

```bash
copy .env.example .env      # Windows
# cp .env.example .env      # Linux / macOS
```

Lihat bagian [Login Email + OTP](#-login-email--otp) untuk panduan App Password Gmail.
</details>

---

## 🔐 Login: Email + OTP

Alur masuk ke **situs** (berbeda dari panel admin) dilakukan dalam dua langkah:

```
┌─────────────────────┐     ┌──────────────────────┐     ┌─────────────────┐
│  1. Email + Password │ ──▶ │  2. OTP ke email Anda │ ──▶ │  ✅ Berhasil masuk │
└─────────────────────┘     └──────────────────────┘     └─────────────────┘
```

1. Buka **Masuk**, isi **email** & **password** terdaftar → klik _Kirim Kode OTP_.
2. Sistem mengirim **kode OTP 6 digit** ke email tersebut.
3. Masukkan kode OTP → berhasil masuk. Kode berlaku **5 menit** & sekali pakai.

### Mengirim OTP ke email ASLI (Gmail)

Konfigurasi email dibaca dari file **`.env`** (tidak ikut Git demi keamanan).

1. Aktifkan **Verifikasi 2 Langkah**: <https://myaccount.google.com/security>
2. Buat **App Password** 16 karakter: <https://myaccount.google.com/apppasswords>
3. Isi file `.env`:
   ```env
   EMAIL_HOST_USER=email_anda@gmail.com
   EMAIL_HOST_PASSWORD=abcdefghijklmnop   # App Password, tanpa spasi
   ```
4. Jalankan ulang `python manage.py runserver` → OTP kini masuk ke inbox.

> ℹ️ Selama `EMAIL_HOST_PASSWORD` **kosong**, sistem otomatis memakai **mode konsol** —
> kode OTP dicetak di terminal (`Kode OTP untuk masuk ... adalah: XXXXXX`). Praktis untuk
> pengembangan tanpa perlu SMTP. Provider selain Gmail cukup mengganti `EMAIL_HOST`.

---

## 👤 Akun Contoh

Data berikut dibuat otomatis oleh `python manage.py seed_data`:

| Peran | Email _(login situs)_ | Password | Username |
|-------|:---------------------:|:--------:|:--------:|
| 👑 Admin / superuser | `admin@blogwahyu.id` | `admin12345` | `admin` |
| 🧑 Pengguna biasa | `wahyu@blogwahyu.id` | `wahyu12345` | `wahyu` |

> **Panel Admin** (`/admin/`) memakai **username + password** biasa — **tanpa OTP**.
> Login situs (`/accounts/login/`) memakai **email + OTP**.

---

## 🗂️ Struktur Proyek

```
project6/
├── django_project/          # Konfigurasi inti (settings, urls, wsgi/asgi)
├── blog/                    # App utama blog
│   ├── models.py            #   Post, Category, Comment
│   ├── views.py             #   CRUD + pencarian + komentar
│   ├── templatetags/        #   Filter avatar & cache-busting CSS
│   └── management/commands/  #   seed_data.py
├── accounts/                # App autentikasi
│   ├── models.py            #   EmailOTP
│   └── views.py             #   Login email, verifikasi OTP, signup
├── templates/               # base.html · blog/ · registration/
├── static/css/              # Tema kustom bergaya Threads
├── media/                   # Unggahan gambar (dibuat saat runtime)
├── .env.example             # Contoh konfigurasi email
└── requirements.txt
```

---

## 🧪 Pengujian

Aplikasi dilengkapi **26 unit test** (model, view, otorisasi, komentar, & alur OTP).

```bash
python manage.py test
```

```
Ran 26 tests in ~11s
OK ✅
```

---

## 🧭 Evolusi project-1 → project6

| Tahap | Fitur yang dilanjutkan |
|:-----:|------------------------|
| **project-1** | Struktur dasar proyek Django + `.venv` |
| **project_2 / project_3** | Halaman ber-styling & Bootstrap 5 |
| **project4** | Template inheritance, Class-Based Views, context data |
| **project5** | Model + `ListView` + admin + unit test |
| **🚀 project6** | Blog penuh: CRUD, Email+OTP, komentar, kategori, tema Threads |

---

<div align="center">

**Blog Wahyu** — dibuat dengan ❤️ dan **Django 6**

_Pengembangan Aplikasi Web (Framework) · Semester 6_

</div>
