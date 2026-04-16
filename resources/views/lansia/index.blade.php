@extends('layouts.app')

@section('page-title', 'Data Lansia')
@section('title', 'Data Lansia - GIS Lansia')

@section('content')
    <div class="space-y-6">
        <!-- Flash Messages -->
        @if ($message = session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    <span class="text-green-800">{{ $message }}</span>
                </div>
                <button class="text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if ($message = session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    <span class="text-red-800">{{ $message }}</span>
                </div>
                <button class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Map Container -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div id="map" class="w-full h-96"></div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 flex justify-between items-center flex-wrap gap-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <input type="text" placeholder="Cari nama atau NIK..."
                        class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="searchInput">
                    <select
                        class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Filter</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non Aktif</option>
                    </select>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button id="importBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2 text-sm font-medium">
                        <i class="fas fa-download"></i> Import
                    </button>
                    <a href="{{ route('lansia.export') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center gap-2 text-sm font-medium">
                        <i class="fas fa-upload"></i> Export
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Cari</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">NIK</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Tgl Lahir</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Umur</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Kondisi</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Tgl Pendataan</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Pendata</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-800">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($lansia as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $item->nama }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $item->nik }}</td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ $item->tanggal_lahir ? date('d/m/Y', strtotime($item->tanggal_lahir)) : '-' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $item->umur ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ $item->created_at ? date('d/m/Y', strtotime($item->created_at)) : '-' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $item->user->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($item->status === 'aktif')
                                        <span class="inline-flex items-center gap-1">
                                            <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">TERDAFTAR</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1">
                                            <span class="inline-block w-2 h-2 rounded-full bg-red-500"></span>
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">NONAKTIF</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('lansia.show', $item->uuid) }}" title="Lihat"
                                            class="inline-block w-8 h-8 rounded border border-blue-300 text-blue-600 hover:bg-blue-50 flex items-center justify-center transition">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('lansia.edit', $item->uuid) }}" title="Edit"
                                            class="inline-block w-8 h-8 rounded border border-yellow-300 text-yellow-600 hover:bg-yellow-50 flex items-center justify-center transition">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('lansia.destroy', $item->uuid) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="w-8 h-8 rounded border border-red-300 text-red-600 hover:bg-red-50 flex items-center justify-center transition">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                    <p>Belum ada data lansia</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center bg-gray-50">
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-700">Tampilkan</span>
                    <select class="px-3 py-1 border border-gray-300 rounded focus:outline-none">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-gray-700">entri</span>
                </div>
                <div class="text-sm">
                    {{ $lansia->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        // Initialize Map - Lombok Tengah coordinates
        const map = L.map('map').setView([-8.58389, 116.3], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Add Markers dari data lansia
        const lansiaData = @json($lansia->items());
        lansiaData.forEach(item => {
            if (item.latitude && item.longitude) {
                L.circleMarker([parseFloat(item.latitude), parseFloat(item.longitude)], {
                    radius: 6,
                    fillColor: '#ef4444',
                    color: '#dc2626',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map).bindPopup(
                    `<b>${item.nama}</b><br>NIK: ${item.nik}<br>${item.alamat || 'Alamat tidak ada'}`);
            }
        });

        // Handle Import Button
        document.getElementById('importBtn').addEventListener('click', function() {
            document.getElementById('importModal').classList.remove('hidden');
        });

        document.getElementById('closeImportModal').addEventListener('click', function() {
            document.getElementById('importModal').classList.add('hidden');
        });

        document.getElementById('importModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4" style="z-index: 9999;">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Import Data Lansia</h2>
                <button id="closeImportModal" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <form action="{{ route('lansia.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File Excel
                    </label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-600 mt-1">Format: .xlsx, .xls, atau .csv</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <p class="text-xs text-blue-800">
                        <strong>Tips:</strong> Pastikan file Excel memiliki kolom sesuai format:
                        Nama, NIK, Tgl Lahir, Umur, Kondisi, Alamat, Desa, Kecamatan, Kabupaten, RT, RW, Latitude,
                        Longitude, Status
                    </p>
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" id="cancelImportBtn"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('cancelImportBtn').addEventListener('click', function() {
            document.getElementById('importModal').classList.add('hidden');
        });
    </script>
@endsection
