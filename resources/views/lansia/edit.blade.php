@extends('layouts.app')

@section('page-title', 'Edit Data Lansia')
@section('title', 'Edit Data Lansia - GIS Lansia')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-bold text-gray-900">Edit Data Lansia</h1>
                @php
                    $statusConfig = [
                        'dikonfirmasi' => ['bg-green-100 text-green-700 border-green-200', '✓ Dikonfirmasi'],
                        'pending' => ['bg-yellow-100 text-yellow-700 border-yellow-200', '⏳ Menunggu Konfirmasi'],
                        'ditolak' => ['bg-red-100 text-red-700 border-red-200', '✗ Ditolak'],
                        'meninggal' => ['bg-gray-100 text-gray-600 border-gray-200', '✦ Meninggal'],
                    ];
                    $cfg = $statusConfig[$lansia->status] ?? [
                        'bg-gray-100 text-gray-500 border-gray-200',
                        $lansia->status,
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full border text-xs font-semibold {{ $cfg[0] }}">
                    {{ $cfg[1] }}
                </span>
            </div>

            <form action="{{ route('lansia.update', $lansia->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lansia *</label>
                        <input type="text" name="nama" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('nama', $lansia->nama) }}">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK *</label>
                        <input type="text" name="nik" required maxlength="16"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono"
                            value="{{ old('nik', $lansia->nik) }}">
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" required id="tanggalLahirInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('tanggal_lahir', $lansia->tanggal_lahir) }}">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Umur --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Umur (tahun) *</label>
                        <input type="number" name="umur" required id="umurInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('umur', $lansia->umur) }}">
                        @error('umur')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat *</label>
                        <input type="text" name="alamat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            placeholder="Jln. ... No. ..." value="{{ old('alamat', $lansia->alamat) }}">
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Desa --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Desa *</label>
                        <input type="text" name="desa" required id="desaInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('desa', $lansia->desa) }}">
                        @error('desa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kecamatan *</label>
                        <input type="text" name="kecamatan" required id="kecamatanInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('kecamatan', $lansia->kecamatan) }}">
                        @error('kecamatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kabupaten --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kabupaten *</label>
                        <input type="text" name="kabupaten" required id="kabupatenInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('kabupaten', $lansia->kabupaten) }}">
                        @error('kabupaten')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Provinsi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Provinsi *</label>
                        <input type="text" name="provinsi" required id="provinsiInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('provinsi', $lansia->provinsi) }}">
                        @error('provinsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- RT --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">RT *</label>
                        <input type="text" name="rt" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('rt', $lansia->rt) }}">
                        @error('rt')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- RW --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">RW *</label>
                        <input type="text" name="rw" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            value="{{ old('rw', $lansia->rw) }}">
                        @error('rw')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kondisi Kesehatan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Kesehatan</label>
                        <input type="text" name="note"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                            placeholder="Kondisi kesehatan lansia" value="{{ old('note', $lansia->note) }}">
                    </div>

                    {{-- STATUS — role-aware --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        @if (auth()->user()->hasRole('admin'))
                            {{-- Admin: bisa ubah ke semua nilai --}}
                            <select name="status" id="statusSelect"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="dikonfirmasi"
                                    {{ old('status', $lansia->status) === 'dikonfirmasi' ? 'selected' : '' }}>
                                    ✓ Dikonfirmasi
                                </option>
                                <option value="pending"
                                    {{ old('status', $lansia->status) === 'pending' ? 'selected' : '' }}>
                                    ⏳ Menunggu Konfirmasi
                                </option>
                                <option value="ditolak"
                                    {{ old('status', $lansia->status) === 'ditolak' ? 'selected' : '' }}>
                                    ✗ Ditolak
                                </option>
                                <option value="meninggal"
                                    {{ old('status', $lansia->status) === 'meninggal' ? 'selected' : '' }}>
                                    ✦ Meninggal
                                </option>
                            </select>
                            <p class="text-xs text-blue-600 mt-1">
                                <i class="fas fa-info-circle"></i>
                                Ubah status di sini untuk konfirmasi / tolak data dari petugas.
                            </p>
                        @else
                            {{-- Petugas: tidak bisa ubah status, tampil readonly --}}
                            <input type="hidden" name="status" value="{{ $lansia->status }}">
                            @php
                                $roLabels = [
                                    'dikonfirmasi' => ['bg-green-100 text-green-700', '✓ Dikonfirmasi'],
                                    'pending' => ['bg-yellow-100 text-yellow-700', '⏳ Menunggu Konfirmasi'],
                                    'ditolak' => ['bg-red-100 text-red-700', '✗ Ditolak'],
                                    'meninggal' => ['bg-gray-100 text-gray-600', '✦ Meninggal'],
                                ];
                                $ro = $roLabels[$lansia->status] ?? ['bg-gray-100 text-gray-500', $lansia->status];
                            @endphp
                            <div
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 flex items-center gap-2">
                                <span
                                    class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ $ro[0] }}">
                                    {{ $ro[1] }}
                                </span>
                                <span class="text-xs text-gray-400">(hanya admin yang dapat mengubah status)</span>
                            </div>
                        @endif
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Latitude --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Latitude</label>
                        <input type="text" name="latitude" id="latitudeInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono"
                            placeholder="Otomatis terisi atau isi manual"
                            value="{{ old('latitude', $lansia->latitude) }}">
                    </div>

                    {{-- Longitude --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Longitude</label>
                        <input type="text" name="longitude" id="longitudeInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-mono"
                            placeholder="Otomatis terisi atau isi manual"
                            value="{{ old('longitude', $lansia->longitude) }}">
                    </div>

                    {{-- Tombol Cari Koordinat --}}
                    <div class="md:col-span-2">
                        <button type="button" id="btnGeocode" onclick="autoGeocode(true)"
                            class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ old('status', $lansia->status) === 'ditolak' ? 'disabled' : '' }}>
                            <svg id="geoIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span id="geoLabel">Cari Koordinat Otomatis</span>
                        </button>
                        <p id="geoHelperText" class="text-xs text-gray-500 mt-1">
                            {{ old('status', $lansia->status) === 'ditolak' ? 'Lansia dengan status ditolak tidak dapat diakses koordinatnya.' : 'Pastikan Desa, Kecamatan, dan Kabupaten sudah terisi sebelum klik tombol ini.' }}
                        </p>
                        <div id="geoStatus" class="mt-2 text-sm hidden"></div>
                    </div>

                </div>{{-- end grid --}}

                {{-- Action Buttons --}}
                <div class="flex gap-3 justify-end mt-8 pt-5 border-t border-gray-100">
                    <a href="{{ route('lansia.index') }}"
                        class="px-5 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── Elemen ───────────────────────────────────────────────────────────────────
        const latitudeInput = document.getElementById('latitudeInput');
        const longitudeInput = document.getElementById('longitudeInput');
        const btnGeocode = document.getElementById('btnGeocode');
        const geoLabel = document.getElementById('geoLabel');
        const geoIcon = document.getElementById('geoIcon');
        const geoStatus = document.getElementById('geoStatus');

        // ── Auto-trigger saat blur dari desa / kecamatan / kabupaten ─────────────────
        ['desaInput', 'kecamatanInput', 'kabupatenInput'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) el.addEventListener('blur', function() {
                autoGeocode(false);
            });
        });

        // ── Fungsi geocoding ─────────────────────────────────────────────────────────
        function autoGeocode(isManual) {
            const desa = document.getElementById('desaInput').value.trim();
            const kecamatan = document.getElementById('kecamatanInput').value.trim();
            const kabupaten = document.getElementById('kabupatenInput').value.trim();
            const provinsi = document.getElementById('provinsiInput').value.trim();

            if (!desa || !kecamatan || !kabupaten) {
                if (isManual) showStatus('⚠ Isi Desa, Kecamatan, dan Kabupaten terlebih dahulu.', 'orange');
                return;
            }

            setLoading(true);
            showStatus('⏳ Sedang mencari koordinat...', 'blue');

            fetch('{{ route('api.geocode') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        desa,
                        kecamatan,
                        kabupaten,
                        provinsi
                    })
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    setLoading(false);
                    if (data.success) {
                        latitudeInput.value = data.latitude;
                        longitudeInput.value = data.longitude;

                        // Auto-fill Alamat dengan format: Desa, Kecamatan, Kabupaten
                        const desa = document.getElementById('desaInput').value.trim();
                        const kecamatan = document.getElementById('kecamatanInput').value.trim();
                        const kabupaten = document.getElementById('kabupatenInput').value.trim();
                        const alamatValue = [desa, kecamatan, kabupaten].filter(Boolean).join(', ');

                        const alamatInput = document.querySelector('input[name="alamat"]');
                        if (alamatInput && alamatValue) {
                            alamatInput.value = alamatValue;
                        }

                        const desaStrategies = [
                            'structured-village', 'structured-village-prefix',
                            'freetext-full-labeled', 'freetext-full',
                            'structured-city-fallback', 'structured-town-fallback',
                        ];
                        const strategyMap = {
                            'structured-village': 'titik desa ditemukan di peta ✅',
                            'structured-village-prefix': 'titik desa ditemukan di peta ✅',
                            'freetext-full-labeled': 'titik desa ditemukan di peta ✅',
                            'freetext-full': 'titik desa ditemukan di peta ✅',
                            'structured-city-fallback': 'desa ditemukan (terdaftar sebagai kota/kelurahan) ✅',
                            'structured-town-fallback': 'desa ditemukan (terdaftar sebagai town) ✅',
                            'kecamatan-fallback': 'desa belum terdaftar di peta, menggunakan titik KECAMATAN ⚠',
                            'kabupaten-only': 'desa & kecamatan belum terdaftar, menggunakan titik KABUPATEN ⚠',
                        };

                        const isDesaFound = desaStrategies.includes(data.strategy);
                        const strategyNote = strategyMap[data.strategy] ?? (data.strategy ?? 'koordinat ditemukan');
                        const displayNote = data.display_name ? '\n📍 Lokasi: ' + data.display_name : '';

                        if (isDesaFound) {
                            showStatus('✓ Koordinat berhasil diperbarui!\n🏘 ' + strategyNote + displayNote, 'green');
                        } else {
                            showStatus('✓ Koordinat ditemukan, namun:\n⚠ ' + strategyNote + displayNote +
                                '\n\nTips: Pastikan nama desa sudah benar atau isi koordinat manual.', 'orange');
                        }
                    } else {
                        showStatus('⚠ ' + (data.message ?? 'Alamat tidak ditemukan.'), 'red');
                    }
                })
                .catch(function() {
                    setLoading(false);
                    showStatus('✗ Gagal terhubung ke layanan geocoding. Coba lagi.', 'red');
                });
        }

        function setLoading(loading) {
            btnGeocode.disabled = loading;
            geoLabel.textContent = loading ? 'Mencari...' : 'Cari Koordinat Otomatis';
            geoIcon.innerHTML = loading ?
                '<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>';
        }

        function showStatus(text, color) {
            const colorMap = {
                green: 'text-green-700 bg-green-50 border-green-200',
                red: 'text-red-700 bg-red-50 border-red-200',
                orange: 'text-orange-700 bg-orange-50 border-orange-200',
                blue: 'text-blue-700 bg-blue-50 border-blue-200',
            };
            geoStatus.className = 'mt-2 text-sm px-3 py-2 rounded border whitespace-pre-line ' + (colorMap[color] ?? '');
            geoStatus.textContent = text;
            geoStatus.classList.remove('hidden');
            if (color === 'green') {
                setTimeout(function() {
                    geoStatus.classList.add('hidden');
                }, 6000);
            }
        }

        // ── Auto umur dari tanggal lahir ─────────────────────────────────────────────
        const tanggalLahirInput = document.getElementById('tanggalLahirInput');
        const umurInput = document.getElementById('umurInput');
        if (tanggalLahirInput) {
            tanggalLahirInput.addEventListener('change', function() {
                if (this.value) {
                    const birthDate = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const mDiff = today.getMonth() - birthDate.getMonth();
                    if (mDiff < 0 || (mDiff === 0 && today.getDate() < birthDate.getDate())) age--;
                    if (umurInput) umurInput.value = age;
                }
            });
        }

        // ── Real-time status change handler ───────────────────────────────────────────
        const statusSelect = document.getElementById('statusSelect');
        const statusHiddenInput = document.querySelector('input[name="status"]');

        // Untuk Admin: track status select change
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                updateCoordinateFields(this.value);
            });
            // Setup awal saat page load
            updateCoordinateFields(statusSelect.value);
        }
        // Untuk Petugas: check status hidden input
        else if (statusHiddenInput) {
            updateCoordinateFields(statusHiddenInput.value);
        }

        function updateCoordinateFields(status) {
            const isRejected = status === 'ditolak';
            const geoHelperText = document.getElementById('geoHelperText');

            // Update latitude & longitude inputs
            if (latitudeInput) latitudeInput.disabled = isRejected;
            if (longitudeInput) longitudeInput.disabled = isRejected;

            // Update geocode button
            btnGeocode.disabled = isRejected;

            // Update styling & clear values jika ditolak
            if (isRejected) {
                if (latitudeInput) {
                    latitudeInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    latitudeInput.value = ''; // Clear nilai
                }
                if (longitudeInput) {
                    longitudeInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    longitudeInput.value = ''; // Clear nilai
                }
                if (geoHelperText) geoHelperText.textContent =
                    'Lansia dengan status ditolak tidak dapat diakses koordinatnya.';
            } else {
                if (latitudeInput) latitudeInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                if (longitudeInput) longitudeInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                if (geoHelperText) geoHelperText.innerHTML =
                    'Pastikan <strong>Desa</strong>, <strong>Kecamatan</strong>, dan <strong>Kabupaten</strong> sudah terisi sebelum klik tombol ini.';
            }
        }
    </script>
@endsection
