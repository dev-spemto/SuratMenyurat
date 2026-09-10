<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administrasi Surat - SMP Muhammadiyah Tonjong')</title>

    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --green: #15803d;
            --green-dark: #166534;
            --green-light: #dcfce7;
            --green-soft: #f0fdf4;
            --text: #17201a;
            --muted: #6b7280;
            --border: #e5e7eb;
            --background: #f5f7f6;
            --white: #ffffff;
            --sidebar-width: 250px;
        }

        body {
            margin: 0;
            font-family: 'Inter', Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        /* SIDEBAR CONTAINER FIXED */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #166534 0%, #14532d 100%);
            color: white;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .brand {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
        }

        .brand-top {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .school-name {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.3;
            color: #ffffff;
        }

        .school-subtitle {
            font-size: 11px;
            margin-top: 2px;
            color: #bbf7d0;
        }

        /* SIDEBAR NAVIGATION MENU (ISOLATED FROM BOOTSTRAP) */
        .sidebar-menu {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-section-title {
            font-size: 10px;
            font-weight: 700;
            color: #86efac;
            text-transform: uppercase;
            padding: 12px 10px 4px 10px;
            letter-spacing: .5px;
            display: block;
            width: 100%;
        }

        .sidebar-link {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 10px 12px !important;
            border-radius: 8px !important;
            color: #ecfdf5 !important;
            text-decoration: none !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            transition: all .2s ease !important;
            width: 100% !important;
            box-sizing: border-box !important;
            white-space: nowrap !important;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .12) !important;
            color: #ffffff !important;
        }

        .sidebar-link.active {
            background: #ffffff !important;
            color: #166534 !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08) !important;
        }

        .sidebar-link .nav-icon {
            width: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            text-align: center;
        }

        /* SIDEBAR FOOTER */
        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid rgba(255, 255, 255, .12);
            font-size: 10px;
            color: #bbf7d0;
            line-height: 1.5;
            text-align: center;
        }

        /* MAIN CONTENT AREA */
        .main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* TOPBAR */
        .topbar {
            height: 65px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .toggle-btn {
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            color: var(--green-dark);
            transition: all .2s;
        }

        .toggle-btn:hover {
            background: var(--green-soft);
            border-color: var(--green);
        }

        .page-title {
            font-size: 16px;
            font-weight: 700;
        }

        .page-subtitle {
            color: var(--muted);
            font-size: 11px;
            margin-top: 2px;
        }

        .topbar-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--green-soft);
            color: var(--green-dark);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .online-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        .footer {
            padding: 16px 28px;
            text-align: center;
            color: #9ca3af;
            font-size: 11px;
            border-top: 1px solid var(--border);
            background: white;
        }

        .footer strong {
            color: var(--green-dark);
        }

        /* SIDEBAR TOGGLE STATES */
        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-collapsed .main {
            margin-left: 0;
        }

        @media (max-width: 800px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main {
                margin-left: 0;
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            .topbar {
                padding: 0 18px;
            }

            .content {
                padding: 18px;
            }

            .footer {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- SIDEBAR NAVIGASI --}}
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-top">
                <div class="logo">
                    <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo SMP Muhammadiyah Tonjong">
                </div>
                <div>
                    <div class="school-name">SMP Muhammadiyah Tonjong</div>
                    <div class="school-subtitle">Administrasi Surat</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-menu">
            <!-- MENU UTAMA -->
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> <span>Dashboard</span>
            </a>

            <!-- REGISTER PERSURATAN -->
            <div class="nav-section-title">Register Agenda</div>
            <a href="{{ route('surat-masuk.index') }}" class="sidebar-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
                <span class="nav-icon">📥</span> <span>Surat Masuk</span>
            </a>
            <!-- RUTE DIKEMBALIKAN KE REGISTRATION AGENDA SURAT KELUAR MURNI -->
            <a href="{{ route('surat-keluar.index') }}" class="sidebar-link {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
                <span class="nav-icon">📄</span> <span>Surat Keluar</span>
            </a>

            <!-- PEMBUATAN / GENERATOR SURAT -->
            <div class="nav-section-title">Pembuatan Dokumen</div>
            <a href="{{ route('surat.index', ['jenis' => 'sppd']) }}" class="sidebar-link {{ request('jenis') == 'sppd' || request('type') == 'sppd' ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-signpost-split-fill"></i></span> <span>SPPD & Surat Tugas</span>
            </a>
            <a href="{{ route('surat.index', ['jenis' => 'aktif_mengajar']) }}" class="sidebar-link {{ request('jenis') == 'aktif_mengajar' || request('type') == 'aktif_mengajar' ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-person-workspace"></i></span> <span>Ket. Aktif Mengajar</span>
            </a>
            <a href="{{ route('surat.index', ['jenis' => 'aktif_belajar']) }}" class="sidebar-link {{ request('jenis') == 'aktif_belajar' || request('type') == 'aktif_belajar' ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-mortarboard-fill"></i></span> <span>Ket. Aktif Belajar</span>
            </a>

            {{-- MENU MODUL BARU (UNDANGAN, PEMBERITAHUAN, PERMOHONAN) --}}
            <a href="{{ route('surat.index', ['jenis' => 'und']) }}" class="sidebar-link {{ request('jenis') == 'und' || request('type') == 'und' ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-envelope-open-fill"></i></span> <span>Undangan</span>
            </a>
            <a href="{{ route('surat.index', ['jenis' => 'pbh']) }}" class="sidebar-link {{ request('jenis') == 'pbh' || request('type') == 'pbh' ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-megaphone-fill"></i></span> <span>Pemberitahuan</span>
            </a>
            <a href="{{ route('surat.index', ['jenis' => 'pmh']) }}" class="sidebar-link {{ request('jenis') == 'pmh' || request('type') == 'pmh' ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></span> <span>Permohonan</span>
            </a>

            <a href="{{ route('surat.index', ['jenis' => 'custom']) }}" class="sidebar-link {{ request('jenis') == 'custom' || request('type') == 'custom' ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-file-earmark-plus-fill"></i></span> <span>Custom / Lainnya</span>
            </a>

            <!-- MASTER DATA -->
            <div class="nav-section-title">Master Data</div>
            <a href="{{ route('pegawai.index') }}" class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-people-fill"></i></span> <span>Data Pegawai / Guru</span>
            </a>
            <a href="{{ route('siswa.index') }}" class="sidebar-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-mortarboard-fill"></i></span> <span>Data Siswa</span>
            </a>
            <a href="{{ route('format-surat.index') }}" class="sidebar-link {{ request()->routeIs('format-surat.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="bi bi-download"></i></span> <span>Download Format Surat</span>
            </a>

            <!-- DATABASE & SYSTEM -->
            <div class="nav-section-title">Database</div>
            <a href="{{ route('pengaturan.index') }}" class="sidebar-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span> <span>Pengaturan</span>
            </a>
            <a href="{{ route('backup.create') }}" class="sidebar-link {{ request()->routeIs('backup.create') ? 'active' : '' }}">
                <span class="nav-icon">💾</span> <span>Backup</span>
            </a>
            <a href="{{ route('backup.restore.form') }}" class="sidebar-link {{ request()->routeIs('backup.restore*') ? 'active' : '' }}">
                <span class="nav-icon">♻️</span> <span>Restore</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            Sistem Administrasi Terpadu<br>
            <strong>SMP Muhammadiyah Tonjong</strong>
        </div>
    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="toggle-btn" id="sidebarToggle" title="Sembunyikan / Tampilkan Sidebar">
                    ☰
                </button>
                <div>
                    <div class="page-title">@yield('page-title', 'Administrasi Surat')</div>
                    <div class="page-subtitle">Sistem Penomoran, Agenda Surat & Generator Dokumen</div>
                </div>
            </div>
            <div class="topbar-badge">
                <span class="online-dot"></span> Sistem Aktif
            </div>
        </header>

        <section class="content">
            {{-- Flash Alert Sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><strong>Sukses:</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Flash Alert Error --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Perhatian:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </section>

        <footer class="footer">
            © {{ date('Y') }} <strong>Tim IT</strong> SMP Muhammadiyah Tonjong. All rights reserved.
        </footer>
    </main>

    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Toggle Sidebar Terpadu -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const body = document.body;

            if (localStorage.getItem('sidebarState') === 'collapsed') {
                body.classList.add('sidebar-collapsed');
            }

            toggleBtn?.addEventListener('click', function () {
                if (window.innerWidth <= 800) {
                    body.classList.toggle('sidebar-open');
                } else {
                    body.classList.toggle('sidebar-collapsed');
                    if (body.classList.contains('sidebar-collapsed')) {
                        localStorage.setItem('sidebarState', 'collapsed');
                    } else {
                        localStorage.setItem('sidebarState', 'expanded');
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>