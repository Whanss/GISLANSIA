@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('title', 'Dashboard - GIS Lansia')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Briefmarksai -->
            <div class="bg-green-500 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Briefmarksai</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total_lansia'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded flex items-center justify-center">
                        <i class="fas fa-folder text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Messaging Konfirmasi -->
            <div class="bg-blue-500 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Messaging Konfirmasi</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['lansia_today'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded flex items-center justify-center">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Diskusi -->
            <div class="bg-red-500 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Diskusi</p>
                        <p class="text-3xl font-bold mt-2">0</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded flex items-center justify-center">
                        <i class="fas fa-comments text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Manajemen -->
            <div class="bg-yellow-500 rounded-lg shadow p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Manajemen</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['petugas_active'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded flex items-center justify-center">
                        <i class="fas fa-cogs text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Placeholder -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Peta Distribusi Lansia</h3>
            </div>
            <div class="bg-gray-100 h-96 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-map text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Map akan muncul di sini (Leaflet)</p>
                </div>
            </div>
        </div>
    </div>
@endsection
