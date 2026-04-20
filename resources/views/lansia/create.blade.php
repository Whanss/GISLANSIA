@extends('layouts.app')

@section('page-title', 'Tambah Data Lansia')
@section('title', 'Tambah Data Lansia - GIS Lansia')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Data Lansia</h1>

            <form action="{{ route('lansia.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lansia *</label>
                        <input type="text" name="nama" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nama Lansia" value="{{ old('nama') }}">
                        @error('nama')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK *</label>
                        <input type="text" name="nik" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="No. Identitas Kependudukan" value="{{ old('nik') }}">
                        @error('nik')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Umur -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Umur (tahun) *</label>
                        <input type="number" name="umur" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Umur" value="{{ old('umur') }}">
                        @error('umur')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                        <input type="text" name="alamat" id="alamatInput" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Jln. ... Nomor ..." value="{{ old('alamat') }}">
                        <p class="text-xs text-gray-600 mt-1">Saat mencari alamat, koordinat akan otomatis terisi</p>
                        @error('alamat')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Desa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desa *</label>
                        <input type="text" name="desa" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Desa / Kelurahan" value="{{ old('desa') }}">
                        @error('desa')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan *</label>
                        <input type="text" name="kecamatan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Kecamatan" value="{{ old('kecamatan') }}">
                        @error('kecamatan')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kabupaten -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten *</label>
                        <input type="text" name="kabupaten" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Kabupaten" value="{{ old('kabupaten') }}">
                        @error('kabupaten')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
                        <input type="text" name="provinsi" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Provinsi" value="{{ old('provinsi') }}">
                        @error('provinsi')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- RT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RT *</label>
                        <input type="text" name="rt" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="RT" value="{{ old('rt') }}">
                        @error('rt')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- RW -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RW *</label>
                        <input type="text" name="rw" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="RW" value="{{ old('rw') }}">
                        @error('rw')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kondisi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Kesehatan</label>
                        <input type="text" name="note"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Kondisi kesehatan" value="{{ old('note') }}">
                    </div>

                    <!-- Status — hanya admin yang bisa pilih -->
                    @if(auth()->user()->hasRole('admin'))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="dikonfirmasi" {{ old('status','dikonfirmasi') === 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="pending"      {{ old('status') === 'pending'      ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="ditolak"      {{ old('status') === 'ditolak'      ? 'selected' : '' }}>Ditolak</option>
                            <option value="meninggal"    {{ old('status') === 'meninggal'    ? 'selected' : '' }}>Meninggal</option>
                        </select>
                    </div>
                    @else
                    {{-- Petugas: selalu pending, tidak perlu tampil --}}
                    <input type="hidden" name="status" value="pending">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-clock text-yellow-500"></i>
                        <p class="text-sm text-yellow-700">Data yang Anda masukkan akan <strong>menunggu konfirmasi admin</strong> sebelum aktif.</p>
                    </div>
                    @endif

                    <!-- Latitude -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                        <input type="text" name="latitude" id="latitudeInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Otomatis terisi atau isi manual" value="{{ old('latitude') }}">
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                        <input type="text" name="longitude" id="longitudeInput"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Otomatis terisi atau isi manual" value="{{ old('longitude') }}">
                    </div>

                    <!-- Tombol Cari Koordinat -->
                    <div class="md:col-span-2">
                        <button type="button" id="btnGeocode"
                            onclick="autoGeocode(true)"
                            class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50">
                            <svg id="geoIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span id="geoLabel">Cari Koordinat Otomatis</span>
                        </button>
                        <p class="text-xs text-gray-500 mt-1">
                            Pastikan <strong>Desa</strong>, <strong>Kecamatan</strong>, dan <strong>Kabupaten</strong> sudah terisi sebelum klik tombol ini.
                        </p>
                        <div id="geoStatus" class="mt-2 text-sm hidden"></div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2 justify-end mt-8">
                    <a href="{{ route('lansia.index') }}"
                        class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const latitudeInput  = document.getElementById('latitudeInput');
    const longitudeInput = document.getElementById('longitudeInput');
    const btnGeocode     = document.getElementById('btnGeocode');
    const geoLabel       = document.getElementById('geoLabel');
    const geoIcon        = document.getElementById('geoIcon');
    const geoStatus      = document.getElementById('geoStatus');

    // ─── Auto-trigger saat blur dari desa / kecamatan / kabupaten ───
    ['desa', 'kecamatan', 'kabupaten'].forEach(fieldName => {
        const field = document.querySelector(`input[name="${fieldName}"]`);
        if (field) field.addEventListener('blur', () => autoGeocode(false));
    });

    // ─── Fungsi utama geocoding ───────────────────────────────────────
    function autoGeocode(isManual = false) {
        const desa      = document.querySelector('input[name="desa"]').value.trim();
        const kecamatan = document.querySelector('input[name="kecamatan"]').value.trim();
        const kabupaten = document.querySelector('input[name="kabupaten"]').value.trim();
        const provinsi  = document.querySelector('input[name="provinsi"]').value.trim();

        if (!desa || !kecamatan || !kabupaten) {
            if (isManual) showStatus('⚠ Isi Desa, Kecamatan, dan Kabupaten terlebih dahulu.', 'orange');
            return;
        }

        setLoading(true);
        showStatus('⏳ Sedang mencari koordinat...', 'blue');

        fetch(`{{ route('api.geocode') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ desa, kecamatan, kabupaten, provinsi })
        })
        .then(res => res.json())
        .then(data => {
            setLoading(false);
            if (data.success) {
                latitudeInput.value  = data.latitude;
                longitudeInput.value = data.longitude;

                const desaStrategies = [
                    'structured-village',
                    'structured-village-prefix',
                    'freetext-full-labeled',
                    'freetext-full',
                    'structured-city-fallback',
                    'structured-town-fallback',
                ];
                const strategyMap = {
                    'structured-village'         : 'titik desa ditemukan di peta ✅',
                    'structured-village-prefix'  : 'titik desa ditemukan di peta ✅',
                    'freetext-full-labeled'      : 'titik desa ditemukan di peta ✅',
                    'freetext-full'              : 'titik desa ditemukan di peta ✅',
                    'structured-city-fallback'   : 'desa ditemukan (terdaftar sebagai kota/kelurahan) ✅',
                    'structured-town-fallback'   : 'desa ditemukan (terdaftar sebagai town) ✅',
                    'kecamatan-fallback'         : 'desa belum terdaftar di peta, menggunakan titik KECAMATAN ⚠',
                    'kabupaten-only'             : 'desa & kecamatan belum terdaftar, menggunakan titik KABUPATEN ⚠',
                };

                const isDesaFound = desaStrategies.includes(data.strategy);
                const strategyNote = data.strategy
                    ? (strategyMap[data.strategy] ?? data.strategy)
                    : 'koordinat ditemukan';
                const displayNote = data.display_name
                    ? `\n📍 Lokasi terdeteksi: ${data.display_name}`
                    : '';

                if (isDesaFound) {
                    showStatus(`✓ Koordinat berhasil ditemukan!\n🏘 ${strategyNote}${displayNote}`, 'green');
                } else {
                    showStatus(`✓ Koordinat ditemukan, namun:\n⚠ ${strategyNote}${displayNote}\n\nTips: Pastikan nama desa sudah benar atau isi koordinat manual.`, 'orange');
                }
            } else {
                showStatus('⚠ ' + (data.message ?? 'Alamat tidak ditemukan.'), 'red');
            }
        })
        .catch(() => {
            setLoading(false);
            showStatus('✗ Gagal terhubung ke layanan geocoding. Coba lagi.', 'red');
        });
    }

    function setLoading(loading) {
        btnGeocode.disabled = loading;
        geoLabel.textContent = loading ? 'Mencari...' : 'Cari Koordinat Otomatis';
        geoIcon.innerHTML = loading
            ? `<circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25" fill="none"></circle>
               <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>`
            : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>`;
    }

    function showStatus(text, color) {
        const colorMap = {
            green : 'text-green-700 bg-green-50 border-green-200',
            red   : 'text-red-700 bg-red-50 border-red-200',
            orange: 'text-orange-700 bg-orange-50 border-orange-200',
            blue  : 'text-blue-700 bg-blue-50 border-blue-200',
        };
        geoStatus.className = `mt-2 text-sm px-3 py-2 rounded border whitespace-pre-line ${colorMap[color] ?? ''}`;
        geoStatus.textContent = text;
        geoStatus.classList.remove('hidden');
        if (color === 'green') {
            setTimeout(() => geoStatus.classList.add('hidden'), 6000);
        }
    }

    // ─── Auto umur dari tanggal lahir ────────────────────────────────
    const tanggalLahirInput = document.querySelector('input[name="tanggal_lahir"]');
    const umurInput         = document.querySelector('input[name="umur"]');
    if (tanggalLahirInput) {
        tanggalLahirInput.addEventListener('change', function () {
            if (this.value) {
                const birthDate = new Date(this.value);
                const today     = new Date();
                let age         = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;
                if (umurInput) umurInput.value = age;
            }
        });
    }
</script>
@endsection
