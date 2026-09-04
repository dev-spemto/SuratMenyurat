<div align="center">

  <!-- BADGE HEADER -->
  <p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
    <img src="https://img.shields.io/badge/SQLite-Database-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite" />
    <img src="https://img.shields.io/badge/TailwindCSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/Electron-Desktop-47848F?style=for-the-badge&logo=electron&logoColor=white" alt="Electron" />
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License" />
  </p>

  <!-- LOGO & HEADING -->
  <br />
  <img src="applogo.png" alt="Logo Spemto Surat Menyurat" width="140" style="border-radius: 20px;">
  <h1 align="center">⚡ Spemto - Surat Menyurat</h1>
  <p align="center">
    <b>Next-Gen Smart Administration & Automated Document Engine System</b>
    <br />
    <i>Solusi Manajemen Persuratan & Dynamic Generator Dokumen Terpadu Berbasis Desktop & Web</i>
  </p>

  <p align="center">
    <a href="#-fitur-unggulan">Fitur Utama</a> •
    <a href="#-arsitektur--teknologi">Arsitektur</a> •
    <a href="#-panduan-instalasi">Instalasi</a> •
    <a href="#-alur-penggunaan">Alur Kerja</a> •
    <a href="#-kredit--kontributor">Kontributor</a>
  </p>

  ---
</div>

<br />

## 🚀 Sekilas Tentang Sistem

**Spemto - Surat Menyurat** adalah ekosistem aplikasi manajemen administrasi persuratan tingkat lanjut yang dirancang untuk mentransformasi tata kelola berkas sekolah secara digital, efisien, dan presisi. 

Mengkombinasikan keandalan **Laravel 12 Engine** dan kecepatan **Desktop Native App Wrapper**, sistem ini tidak hanya merekap agenda Surat Masuk dan Surat Keluar secara real-time, tetapi juga dilengkapi dengan **Automated Document Generator** otomatis untuk SPPD, Surat Keterangan Aktif Belajar, Surat Keterangan Aktif Mengajar, serta Templating Custom secara mandiri.

---

## 💎 Fitur Unggulan

| Modul | Deskripsi Capabilities |
| :--- | :--- |
| 🗂️ **Centralized Agenda Engine** | Monitoring & pencatatan digital terpadu untuk Surat Masuk dan Surat Keluar dengan pengindeksan nomor urut otomatis anti-duplikat. |
| 🚙 **Automated SPPD Generator** | Pembuat Surat Perintah Perjalanan Dinas (SPPD) interaktif lengkap dengan multiselect daftar pegawai, rincian biaya, dan lampiran resmi. |
| 🎓 **Student & Staff Certificate Engine** | Penerbitan Surat Keterangan Aktif Belajar (Siswa) dan Aktif Mengajar (Guru/Staf) secara serba otomatis terintegrasi Master Data. |
| ✍️ **Custom Document Studio** | Modul fleksibel untuk menyusun surat khusus, undangan, dan pemberitahuan umum dengan layout cetak yang rapi dan responsif. |
| 🔒 **System Master Protection** | Proteksi bawaan (*View Only*) untuk berkas sampel bawaan master system sehingga menjaga integritas database dari ketidaksengajaan hapus/sunting. |
| 🖥️ **Standalone Desktop Package** | Terdistribusi sebagai aplikasi desktop portabel Windows (`.exe`) via Inno Setup Wrapper tanpa ketergantungan koneksi internet publik (*Local-First Architecture*). |

---

## 🛠️ Arsitektur & Teknologi

Eksosistem ini dibangun mengadopsi stack modern dengan performa tinggi dan footprint memori yang ultra-ringan:

- **Core Framework**: Laravel 12
- **Styling Engine**: Tailwind CSS v4
- **Database Layer**: SQLite 3 (Zero-Configuration Portable DB)
- **Document Converter**: Barryvdh Laravel DomPDF
- **Desktop Bundle**: Custom Portable PHP Engine & Inno Setup Deployment Compiler

---

## 💻 Panduan Instalasi (Development Mode)

### Prasyarat System

Pastikan perangkat lokal Anda telah terpasang:
- **PHP** versi `>= 8.2` (dengan ekstensi `pdo_sqlite`, `mbstring`, `fileinfo`, `gd`)
- **Composer** versi `>= 2.x`
- **Node.js & npm** (opsional untuk *compiling frontend assets*)

### Langkah-Langkah Setup

```bash
# 1. Clone repositori ke direktori lokal
git clone [https://github.com/spemto/suratmenyurat.git](https://github.com/spemto/suratmenyurat.git)
cd suratmenyurat

# 2. Install dependensi composer
composer install

# 3. Salin berkas lingkungan (.env)
copy .env.example .env

# 4. Inisialisasi Kunci Aplikasi & Database SQLite
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed

# 5. Jalankan server lokal
php artisan serve
```

Aplikasi dapat diakses langsung via peramban di `http://127.0.0.1:8000`.

---

## 📦 Kompilasi Desktop Installer (Production Package)

Aplikasi telah dilengkapi dengan bundler installer Windows otomatis via **Inno Setup**:

1. Pastikan folder `php_engine` portable dan berkas `run_silent.vbs` telah berada di direktori utama.
2. Jalankan perintah pembersihan cache lokal:
   ```bash
   php artisan optimize:clear
   ```
3. Buka berkas `setup.iss` menggunakan software **Inno Setup Compiler**, lalu klik **Compile (Ctrl + F9)**.
4. Berkas installer siap pakai otomatis terbentuk pada lokasi:
   `InstallerOutput\Setup_Spemto-SuratMenyurat_v1.0.exe`

---

## 📄 Lisensi

Proyek ini terlisensi di bawah MIT License.

---

<div align="center">

  ### 🎨 Kredit & Kontributor

  <p>Didesain, dikembangkan, dan dipelihara dengan penuh ketelitian oleh:</p>

  <b>✨ Tim IT / Tim Kreatif SMP Muhammadiyah Tonjong ✨</b>

  <br />

  <sub><b>SMP MUHAMMADIYAH TONJONG</b><br />
  <i>Unggul, Islami, dan Berkemajuan</i><br />
  Jl. Raya Linggapura No. 46, Kec. Tonjong, Kab. Brebes, Jawa Tengah 52271</sub>

</div>