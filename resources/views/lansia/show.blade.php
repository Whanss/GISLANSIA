@extends('layouts.app')

@section('page-title', 'Detail Lansia')
@section('title', 'Detail Data Lansia - GIS Lansia')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    {{-- ── HEADER CARD ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">

            {{-- Avatar + Nama --}}
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-user text-indigo-500 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $lansia->nama }}</h1>
                    <p class="text-sm text-gray-500 font-mono mt-0.5">NIK: {{ $lansia->nik }}</p>
                </div>
            </div>

            {{-- Status Badge --}}
            @php
                $statusConfig = [
                    'dikonfirmasi' => ['bg-green-100 text-green-700 border-green-200',  'fas fa-check-circle text-green-500', 'Dikonfirmasi'],
                    'pending'      => ['bg-yellow-100 text-yellow-700 border-yellow-200','fas fa-clock text-yellow-500',       'Menunggu Konfirmasi'],
                    'ditolak'      => ['bg-red-100 text-red-700 border-red-200',         'fas fa-times-circle text-red-500',   'Ditolak'],
                    'meninggal'    => ['bg-gray-100 text-gray-600 border-gray-300',      'fas fa-cross text-gray-400',         'Meninggal'],
                ];
                $cfg = $statusConfig[$lansia->status] ?? ['bg-gray-100 text-gray-500 border-gray-200', 'fas fa-question-circle', ucfirst($lansia->status)];
            @endphp
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border text-sm font-semibold {{ $cfg[0] }}">
                <i class="{{ $cfg[1] }} text-xs"></i>
                {{ $cfg[2] }}
            </span>
        </div>
    </div>

    {{-- ── DATA PRIBADI + ALAMAT ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Data Pribadi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-id-card text-indigo-500">No</i>
                <h2 class="font-semibold text-gray-800">Data Pribadi</h2>
            </div>
            <div class="space-y-3">

                <div class="flex justify-between items-start py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500 w-36 shrink-0">Tanggal Lahir</span>
                    <span class="text-sm font-medium text-gray-800 text-right">
                        {{ $lansia->tanggal_lahir ? \Carbon\Carbon::parse($lansia->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>

                <div class="flex justify-between items-start py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500 w-36 shrink-0">Umur</span>
                    <span class="text-sm font-medium text-gray-800">{{ $lansia->umur }} tahun</span>
                </div>

                <div class="flex justify-between items-start py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500 w-36 shrink-0">RT / RW</span>
                    <span class="text-sm font-medium text-gray-800">{{ $lansia->rt }} / {{ $lansia->rw }}</span>
                </div>

                <div class="flex justify-between items-start py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500 w-36 shrink-0">Kondisi Kesehatan</span>
                    <span class="text-sm font-medium text-gray-800 text-right">{{ $lansia->note ?? '-' }}</span>
                </div>

                <div class="flex justify-between items-start py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500 w-36 shrink-0">Pendata</span>
                    <span class="text-sm font-medium text-gray-800">{{ $lansia->pendata ?? ($lansia->user->name ?? '-') }}</span>
                </div>

                <div class="flex justify-between items-start py-2">
                    <span class="text-sm text-gray-500 w-36 shrink-0">Tgl Pendataan</span>
                    <span class="text-sm font-medium text-gray-800">
                        {{ $lansia->created_at ? \Carbon\Carbon::parse($lansia->created_at)->translatedFormat('d F Y') : '-' }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Alamat --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-map-marker-alt text-red-500"></i>
                <h2 class="font-semibold text-gray-800">Alamat</h2>
            </div>
            <div class="space-y-3">

                {{-- Alamat Jalan --}}
                <div class="py-2 border-b border-gray-50">
                    <p class="text-xs text-gray-400 mb-0.5">Alamat Jalan</p>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $lansia->alamat ?: '-' }}
                    </p>
                </div>

                {{-- Wilayah --}}
                <div class="grid grid-cols-2 gap-3 py-2 border-b border-gray-50">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Desa</p>
                        <p class="text-sm font-medium text-gray-800">{{ $lansia->desa ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Kecamatan</p>
                        <p class="text-sm font-medium text-gray-800">{{ $lansia->kecamatan ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 py-2 border-b border-gray-50">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Kabupaten</p>
                        <p class="text-sm font-medium text-gray-800">{{ $lansia->kabupaten ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Provinsi</p>
                        <p class="text-sm font-medium text-gray-800">{{ $lansia->provinsi ?? '-' }}</p>
                    </div>
                </div>

                {{-- Koordinat --}}
                <div class="py-2">
                    <p class="text-xs text-gray-400 mb-1">Koordinat GPS</p>
                    @if($lansia->latitude && $lansia->longitude)
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-mono">
                                <i class="fas fa-arrows-alt-v text-xs"></i> {{ $lansia->latitude }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-mono">
                                <i class="fas fa-arrows-alt-h text-xs"></i> {{ $lansia->longitude }}
                            </span>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Koordinat belum tersedia</p>
                    @endif
                </div>

            </div>
        </div>

    </div>

    {{-- ── PETA LOKASI ──────────────────────────────────────────────────── --}}
    @if($lansia->latitude && $lansia->longitude)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
            <i class="fas fa-map text-blue-500"></i>
            <h2 class="font-semibold text-gray-800">Lokasi di Peta</h2>
            <span class="text-xs text-gray-400 ml-1">
                — {{ $lansia->desa }}, {{ $lansia->kecamatan }}
            </span>
        </div>
        <div id="detailMap" style="height: 320px;"></div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
        <i class="fas fa-map-marked-alt text-4xl mb-3 block text-gray-200"></i>
        <p class="font-medium">Lokasi belum tersedia</p>
        <p class="text-sm mt-1">Data ini belum memiliki koordinat GPS.</p>
        <a href="{{ route('lansia.edit', $lansia->id) }}"
            class="inline-flex items-center gap-2 mt-3 text-sm text-blue-600 hover:underline">
            <i class="fas fa-edit text-xs"></i> Tambahkan koordinat via Edit
        </a>
    </div>
    @endif

    {{-- ── ACTION BUTTONS ───────────────────────────────────────────────── --}}
    <div class="flex flex-wrap gap-3 justify-end pb-4">
        <a href="{{ route('lansia.index') }}"
            class="inline-flex items-center gap-2 px-5 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-xs"></i> Kembali
        </a>
        <a href="{{ route('lansia.edit', $lansia->id) }}"
            class="inline-flex items-center gap-2 px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-edit text-xs"></i> Edit Data
        </a>
    </div>

</div>

{{-- Leaflet map init --}}
@if($lansia->latitude && $lansia->longitude)
<script>
(function() {
    const lat = {{ $lansia->latitude }};
    const lng = {{ $lansia->longitude }};

    const map = L.map('detailMap', { zoomControl: true }).setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    const icon = L.divIcon({
        className: '',
        html: '<div style="width:14px;height:14px;background:#ef4444;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.3)"></div>',
        iconSize: [14, 14],
        iconAnchor: [7, 7],
    });

    L.marker([lat, lng], { icon: icon })
        .addTo(map)
        .bindPopup(
            '<div style="min-width:160px;font-size:13px">' +
            '<p style="font-weight:700;color:#111;margin-bottom:4px">{{ addslashes($lansia->nama) }}</p>' +
            '<p style="color:#6b7280;font-size:11px">{{ addslashes($lansia->desa) }}, {{ addslashes($lansia->kecamatan) }}</p>' +
            '<p style="color:#6b7280;font-size:11px">{{ addslashes($lansia->kabupaten) }}, {{ addslashes($lansia->provinsi) }}</p>' +
            '</div>'
        )
        .openPopup();
})();
</script>
@endif
@endsection
