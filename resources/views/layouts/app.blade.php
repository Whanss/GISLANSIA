{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GIS Lansia')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    {{-- Leaflet MarkerCluster --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css"/>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom cluster icon */
        .custom-cluster {
            background: transparent !important;
            border: none !important;
        }
        .custom-cluster-inner {
            width: 38px;
            height: 38px;
            background: #ef4444;
            border: 3px solid #fff;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            line-height: 1;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen">
<div class="flex h-screen overflow-hidden">

    {{-- ── SIDEBAR ──────────────────────────────────────────────────────── --}}
    <aside id="sidebar"
        class="w-64 bg-gray-800 text-white flex flex-col flex-shrink-0 transition-all duration-300 overflow-hidden">

        {{-- Logo --}}
        <div class="p-6 border-b border-gray-700">
            <div class="flex flex-col items-center text-center">
                <img src="{{ asset('storage/sidebar/LOGO_KABUPATEN_LOMBOK_TENGAH.png') }}"
                    alt="Logo" class="w-16 h-16 rounded-lg object-contain mb-3">
                <h1 class="text-base font-bold leading-tight">GIS Lansia</h1>

            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            @auth
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 text-sm"></i>
                    <span class="ml-3 font-medium text-sm">Dashboard</span>
                </a>

                {{-- DATA --}}
                @can('lansia.view')
                <div class="pt-3">
                    <p class="text-gray-500 text-xs font-semibold uppercase px-4 mb-2 tracking-wider">Data</p>
                    <a href="{{ route('lansia.index') }}"
                        class="flex items-center px-4 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('lansia.*') ? 'bg-gray-700 text-white' : '' }}">
                        <i class="fas fa-users w-5 text-sm"></i>
                        <span class="ml-3 font-medium text-sm">Lansia</span>
                    </a>
                </div>
                @endcan

                @if(auth()->user()->can('user.view') || auth()->user()->can('role.view'))
                    {{-- HAK AKSES --}}
                    <div class="pt-3">
                        <p class="text-gray-500 text-xs font-semibold uppercase px-4 mb-2 tracking-wider">Hak Akses</p>
                        @can('user.view')
                        <a href="{{ route('users.index') }}"
                            class="flex items-center px-4 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('users.*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-user w-5 text-sm"></i>
                            <span class="ml-3 font-medium text-sm">Pengguna</span>
                        </a>
                        @endcan
                        @can('role.view')
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center px-4 py-2.5 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('roles.*') ? 'bg-gray-700 text-white' : '' }}">
                            <i class="fas fa-user-shield w-5 text-sm"></i>
                            <span class="ml-3 font-medium text-sm">Peran & Izin</span>
                        </a>
                          @endcan
                    </div>
                @endif
            @endauth
        </nav>

        {{-- Logout bawah --}}

    </aside>

    {{-- ── MAIN AREA ────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- ── TOPBAR ───────────────────────────────────────────────────── --}}
        <header class="bg-white border-b border-gray-200 flex-shrink-0" style="position:relative;z-index:1001;">
            <div class="px-4 md:px-6 h-16 flex items-center justify-between gap-4">

                {{-- Kiri: hamburger + judul halaman --}}
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Hamburger --}}
                    <button id="sidebarToggle"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors flex-shrink-0"
                        title="Toggle Sidebar">
                        <i class="fas fa-bars text-base"></i>
                    </button>

                    {{-- Judul --}}
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-gray-800 truncate">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>
                </div>

                {{-- Kanan: notifikasi + profile --}}
                <div class="flex items-center gap-2 flex-shrink-0">

                    {{-- Notifikasi Bell --}}
                    @auth
                    @php
                        $pendingCount = \App\Models\Lansia::where('status', 'pending')->count();
                    @endphp
                    <div class="relative" id="notifWrapper">
                        <button id="notifBtn"
                            class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors relative">
                            <i class="fas fa-bell text-base"></i>
                            @if($pendingCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                                    {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Notifikasi --}}
                        <div id="notifDropdown"
                            class="hidden absolute right-0 top-full mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" style="z-index:9999;">
                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-800">Notifikasi</p>
                                @if($pendingCount > 0)
                                    <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full">
                                        {{ $pendingCount }} pending
                                    </span>
                                @endif
                            </div>

                            @if($pendingCount > 0)
                                <div class="px-4 py-3 hover:bg-yellow-50 transition-colors border-b border-gray-50">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-clock text-yellow-600 text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $pendingCount }} data lansia menunggu konfirmasi
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Konfirmasi melalui menu Edit data lansia
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-2.5 bg-gray-50">
                                    <a href="{{ route('lansia.index', ['status' => 'pending']) }}"
                                        class="text-xs text-blue-600 hover:underline font-medium">
                                        Lihat semua data pending →
                                    </a>
                                </div>
                            @else
                                <div class="px-4 py-6 text-center text-gray-400">
                                    <i class="fas fa-check-circle text-3xl mb-2 block text-green-300"></i>
                                    <p class="text-sm">Tidak ada notifikasi</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endauth

                    {{-- Profile Dropdown --}}
                    @auth
                    <div class="relative" id="profileWrapper">
                        <button id="profileBtn"
                            class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff&size=64"
                                alt="Avatar"
                                class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            <div class="text-left hidden sm:block">
                                <p class="text-sm font-semibold text-gray-800 leading-tight max-w-[120px] truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-gray-400 leading-tight">
                                    {{ ucfirst(auth()->user()->getRoleNames()->first() ?? '-') }}
                                </p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs hidden sm:block"></i>
                        </button>

                        {{-- Dropdown Profile --}}
                        <div id="profileDropdown"
                            class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" style="z-index:9999;">

                            {{-- Info user --}}
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                <span class="inline-block mt-1.5 text-xs px-2 py-0.5 rounded-full font-medium
                                    {{ auth()->user()->hasRole('admin') ? 'bg-indigo-100 text-indigo-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst(auth()->user()->getRoleNames()->first() ?? '-') }}
                                </span>
                            </div>

                            {{-- Menu --}}


                            {{-- Logout --}}
                            <div class="border-t border-gray-100 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-4 text-sm"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endauth

                </div>
            </div>
        </header>

        {{-- ── PAGE CONTENT ─────────────────────────────────────────────── --}}
        <main class="flex-1 overflow-y-auto p-5 bg-gray-50">
            @yield('content')
        </main>
    </div>
</div>

{{-- ── SCRIPTS ──────────────────────────────────────────────────────────── --}}
<script>
// ── Sidebar Toggle ────────────────────────────────────────────────────────────
const sidebar       = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
let sidebarOpen     = true;

sidebarToggle.addEventListener('click', function () {
    sidebarOpen = !sidebarOpen;
    if (sidebarOpen) {
        sidebar.style.width = '16rem'; // w-64
        sidebar.style.minWidth = '16rem';
    } else {
        sidebar.style.width = '0';
        sidebar.style.minWidth = '0';
    }
});

// ── Dropdown helper ───────────────────────────────────────────────────────────
function setupDropdown(btnId, dropdownId, wrapperId) {
    const btn      = document.getElementById(btnId);
    const dropdown = document.getElementById(dropdownId);
    const wrapper  = document.getElementById(wrapperId);
    if (!btn || !dropdown) return;

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isHidden = dropdown.classList.contains('hidden');
        // Tutup semua dropdown lain
        document.querySelectorAll('[id$="Dropdown"]').forEach(d => d.classList.add('hidden'));
        if (isHidden) dropdown.classList.remove('hidden');
    });
}

setupDropdown('notifBtn',    'notifDropdown',   'notifWrapper');
setupDropdown('profileBtn',  'profileDropdown', 'profileWrapper');

// Klik di luar → tutup semua dropdown
document.addEventListener('click', function () {
    document.querySelectorAll('[id$="Dropdown"]').forEach(d => d.classList.add('hidden'));
});
</script>

@stack('scripts')
</body>
</html>
