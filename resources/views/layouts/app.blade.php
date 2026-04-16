{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GIS Lansia')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white flex flex-col relative">
            <!-- Logo & Profile -->
            <div class="p-6 border-b border-gray-700">
                <div class="flex flex-col items-center text-center">
                    <img src="{{ asset('storage/sidebar/LOGO_KABUPATEN_LOMBOK_TENGAH.png') }}" alt="Logo"
                        class="w-16 h-16 rounded-lg object-contain mb-3">
                    <div>
                        <h1 class="text-base font-bold">GIS Lansia</h1>
                        <p class="text-gray-400 text-xs">
                            {{ auth()->user()->hasRole('admin') ? 'Administrator' : 'Petugas' }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-4">
                @auth
                    <!-- Dashboard -->
                    @php $activeDash = request()->routeIs('dashboard') ? 'bg-gray-700' : ''; @endphp
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ $activeDash }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="ml-3 font-medium text-sm">Dashboard</span>
                    </a>

                    <!-- DATA Section -->
                    <div>
                        <p class="text-gray-500 text-xs font-semibold uppercase px-4 mb-2">DATA</p>
                        @php $activeLansia = request()->routeIs('lansia.*') ? 'bg-gray-700' : ''; @endphp
                        <a href="{{ route('lansia.index') }}"
                            class="flex items-center px-4 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ $activeLansia }}">
                            <i class="fas fa-users w-5"></i>
                            <span class="ml-3 font-medium text-sm">Lansia</span>
                        </a>
                    </div>

                    @if (auth()->user()->hasRole('admin'))
                        <!-- HAK AKSES Section -->
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase px-4 mb-2">HAK AKSES</p>
                            @php $activeUsers = request()->routeIs('users.*') ? 'bg-gray-700' : ''; @endphp
                            <a href="{{ route('users.index') }}"
                                class="flex items-center px-4 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ $activeUsers }}">
                                <i class="fas fa-user w-5"></i>
                                <span class="ml-3 font-medium text-sm">Pengguna</span>
                            </a>

                            @php $activeRoles = request()->routeIs('roles.*') ? 'bg-gray-700' : ''; @endphp
                            <a href="{{ route('roles.index') }}"
                                class="flex items-center px-4 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white transition-colors {{ $activeRoles }}">
                                <i class="fas fa-user-shield w-5"></i>
                                <span class="ml-3 font-medium text-sm">Peran & lain</span>
                            </a>
                        </div>

                        <!-- PENGATURAN Section -->
                        <div>
                            <p class="text-gray-500 text-xs font-semibold uppercase px-4 mb-2">PENGATURAN</p>
                            <a href="#"
                                class="flex items-center px-4 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                <i class="fas fa-globe w-5"></i>
                                <span class="ml-3 font-medium text-sm">Website</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </nav>

            <!-- Logout Bottom -->
            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-2 rounded text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3 font-medium text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white border-b border-gray-200">
                <div class="px-8 py-5 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-600 mt-1">Selamat datang, {{ auth()->user()->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-xs bg-red-500 text-white px-3 py-1 rounded-full font-semibold">Ad</span>
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random"
                                alt="User Avatar" class="w-full h-full">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
