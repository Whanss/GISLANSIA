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

        <!-- Map Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Search bar overlay di atas peta -->
            <div class="relative">
                <div id="map" class="w-full" style="height: 420px;"></div>

                <!-- Search overlay -->
                <div class="absolute top-3 left-1/2 -translate-x-1/2 w-full max-w-sm px-3" style="z-index: 10000;">
                    <div class="relative">
                        <div class="flex items-center bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                            <i class="fas fa-search text-gray-400 pl-3 pr-2"></i>
                            <input type="text" id="mapSearchInput" placeholder="Cari nama lansia di peta..."
                                autocomplete="off" class="w-full py-2 pr-3 text-sm text-gray-800 focus:outline-none">
                            <button id="clearMapSearch" class="hidden pr-3 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                        <!-- Dropdown hasil -->
                        <div id="mapSearchResults"
                            class="hidden absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-gray-200 max-h-60 overflow-y-auto">
                        </div>
                    </div>
                </div>

                <!-- Legenda -->
                <div class="absolute bottom-6 left-3 bg-white rounded-lg shadow px-3 py-2 text-xs text-gray-600 space-y-1"
                    style="z-index: 10000;">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-500"></span> Aktif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-gray-400"></span> Non Aktif
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3">
                <!-- Search & Filter -->
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="tableSearchInput" value="{{ request('search') }}"
                            placeholder="Cari nama atau NIK..."
                            class="pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-56">
                    </div>
                    <select id="statusFilter"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="dikonfirmasi" {{ request('status') === 'dikonfirmasi' ? 'selected' : '' }}>
                            Dikonfirmasi</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu
                        </option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak
                        </option>
                        <option value="meninggal" {{ request('status') === 'meninggal' ? 'selected' : '' }}>Meninggal
                        </option>
                    </select>
                    <!-- loading indicator -->
                    <span id="filterLoading" class="hidden text-xs text-blue-500 flex items-center gap-1">
                        <i class="fas fa-spinner fa-spin"></i> Memfilter...
                    </span>
                </div>

                <!-- Action buttons -->
                <div class="flex flex-wrap gap-2">
                    <button id="importBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 text-sm font-medium">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                    <a href="{{ route('lansia.export') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 text-sm font-medium">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                    <a href="{{ route('lansia.create') }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 text-sm font-medium">
                        <i class="fas fa-plus"></i> Tambah Lansia
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">NIK</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Tgl Lahir</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Umur</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Koordinat</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Tgl Pendataan</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Pendata</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($lansia as $item)
                            <tr class="hover:bg-gray-50 transition lansia-row" data-lat="{{ $item->latitude }}"
                                data-lng="{{ $item->longitude }}" data-nama="{{ $item->nama }}">
                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $lansia->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    @if ($item->latitude && $item->longitude)
                                        <button type="button"
                                            onclick="flyToLansia({{ $item->latitude }}, {{ $item->longitude }}, '{{ addslashes($item->nama) }}')"
                                            class="text-left hover:text-blue-600 transition flex items-center gap-1 group">
                                            {{ $item->nama }}
                                            <i
                                                class="fas fa-map-marker-alt text-xs text-gray-300 group-hover:text-blue-500 transition"></i>
                                        </button>
                                    @else
                                        {{ $item->nama }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $item->nik }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $item->tanggal_lahir ? date('d/m/Y', strtotime($item->tanggal_lahir)) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $item->umur ? $item->umur . ' th' : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($item->latitude && $item->longitude)
                                        <span class="inline-flex items-center gap-1 text-green-600 text-xs">
                                            <i class="fas fa-map-pin"></i> Ada
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">
                                            <i class="fas fa-map-pin"></i> Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    {{ $item->created_at ? date('d M Y', strtotime($item->created_at)) : '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">{{ $item->user->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusConfig = [
                                            'dikonfirmasi' => [
                                                'bg-green-100 text-green-700',
                                                'bg-green-500',
                                                'DIKONFIRMASI',
                                            ],
                                            'pending' => ['bg-yellow-100 text-yellow-700', 'bg-yellow-500', 'MENUNGGU'],
                                            'ditolak' => ['bg-red-100 text-red-700', 'bg-red-500', 'DITOLAK'],
                                            'meninggal' => ['bg-gray-100 text-gray-600', 'bg-gray-400', 'MENINGGAL'],
                                        ];
                                        $cfg = $statusConfig[$item->status] ?? [
                                            'bg-gray-100 text-gray-500',
                                            'bg-gray-400',
                                            strtoupper($item->status),
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold {{ $cfg[0] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg[1] }} inline-block"></span>
                                        {{ $cfg[2] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-1">
                                        <a href="{{ route('lansia.show', $item->id) }}" title="Lihat"
                                            class="w-8 h-8 rounded border border-blue-200 text-blue-600 hover:bg-blue-50 flex items-center justify-center transition">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('lansia.edit', $item->id) }}" title="Edit"
                                            class="w-8 h-8 rounded border border-yellow-200 text-yellow-600 hover:bg-yellow-50 flex items-center justify-center transition">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('lansia.destroy', $item->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Yakin hapus data {{ $item->nama }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="w-8 h-8 rounded border border-red-200 text-red-600 hover:bg-red-50 flex items-center justify-center transition">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                    <p class="font-medium">Belum ada data lansia</p>
                                    <p class="text-sm mt-1">Silakan tambah data lansia baru</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex flex-wrap justify-between items-center gap-3 bg-gray-50">
                <p class="text-sm text-gray-600">
                    Menampilkan {{ $lansia->firstItem() ?? 0 }}–{{ $lansia->lastItem() ?? 0 }}
                    dari <strong>{{ $lansia->total() }}</strong> data
                </p>
                <div class="text-sm">
                    {{ $lansia->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // ─── DATA LANSIA (semua yg punya koordinat) ───────────────────────────────
        const allLansiaData = @json($allLansiaForMap);

        // ─── HELPER: warna marker per status ─────────────────────────────────────
        const STATUS_COLORS = {
            'dikonfirmasi': {
                fill: '#22c55e',
                border: '#16a34a'
            },
            'pending': {
                fill: '#f59e0b',
                border: '#d97706'
            },
            'meninggal': {
                fill: '#6b7280',
                border: '#4b5563'
            },
        };

        function markerIcon(status) {
            const c = STATUS_COLORS[status] ?? {
                fill: '#9ca3af',
                border: '#6b7280'
            };
            return L.divIcon({
                className: '',
                html: '<div style="width:13px;height:13px;background:' + c.fill +
                    ';border:2.5px solid #fff;border-radius:50%;box-shadow:0 1px 5px rgba(0,0,0,0.4);outline:2px solid ' +
                    c.border + '"></div>',
                iconSize: [13, 13],
                iconAnchor: [6, 6],
                popupAnchor: [0, -8],
            });
        }

        function clusterIcon(cluster) {
            const count = cluster.getChildCount();
            const size = count < 10 ? 36 : count < 100 ? 42 : 48;
            const fs = count < 100 ? 13 : 11;
            return L.divIcon({
                className: 'custom-cluster',
                html: '<div class="custom-cluster-inner" style="width:' + size + 'px;height:' + size +
                    'px;font-size:' + fs + 'px">' + count + '</div>',
                iconSize: [size, size],
                iconAnchor: [size / 2, size / 2],
            });
        }

        function esc(s) {
            if (!s) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // ─── MAP INIT ─────────────────────────────────────────────────────────────
        let map;
        let activePopupMarker = null;

        (function initMap() {
            map = L.map('map', {
                zoomControl: true
            }).setView([-8.58389, 116.3], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);

            // Cluster group — selalu merah angka, tidak pernah spiderfy
            const clusterGroup = L.markerClusterGroup({
                iconCreateFunction: clusterIcon,
                maxClusterRadius: 60,
                spiderfyOnMaxZoom: false,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: false,
                animate: true,
            });

            allLansiaData.forEach(function(item) {
                if (!item.latitude || !item.longitude) return;

                const marker = L.marker(
                    [parseFloat(item.latitude), parseFloat(item.longitude)], {
                        icon: markerIcon(item.status)
                    }
                );

                // Popup single: nama + NIK saja
                marker.bindPopup(
                    '<div style="font-family:sans-serif;min-width:140px">' +
                    '<p style="font-weight:700;font-size:13px;color:#111;margin:0 0 3px">' + esc(item
                    .nama) + '</p>' +
                    '<p style="font-size:11px;color:#6b7280;margin:0">NIK: ' + esc(item.nik) + '</p>' +
                    '</div>', {
                        maxWidth: 200
                    }
                );

                // Simpan data di marker untuk diakses cluster click
                marker._lansiaData = item;
                item._marker = marker;
                clusterGroup.addLayer(marker);
            });

            // Klik cluster → popup show count saja
            clusterGroup.on('clusterclick', function(e) {
                const markers = e.layer.getAllChildMarkers();
                const count = markers.length;
                const latlng = e.layer.getLatLng();

                const content =
                    '<div style="font-family:sans-serif;text-align:center;padding:8px">' +
                    '<p style="font-weight:700;font-size:14px;color:#ef4444;margin:0">' +
                    '<i class="fas fa-location-dot" style="margin-right:6px"></i>' + count + ' lansia</p>' +
                    '<p style="font-size:11px;color:#6b7280;margin:4px 0 0">di lokasi ini</p>' +
                    '</div>';

                L.popup({
                        maxWidth: 160
                    })
                    .setLatLng(latlng)
                    .setContent(content)
                    .openOn(map);

                L.DomEvent.stopPropagation(e);
            });

            map.addLayer(clusterGroup);
        })();

        // ─── FLY TO LANSIA (dipanggil dari baris tabel) ───────────────────────────
        function flyToLansia(lat, lng, nama) {
            map.flyTo([lat, lng], 16, {
                duration: 1.2
            });

            if (activePopupMarker) activePopupMarker.closePopup();

            // Cari marker yang sesuai
            const found = allLansiaData.find(d =>
                Math.abs(parseFloat(d.latitude) - lat) < 0.00001 &&
                Math.abs(parseFloat(d.longitude) - lng) < 0.00001
            );
            if (found && found._marker) {
                setTimeout(function() {
                    found._marker.openPopup();
                }, 1300);
                activePopupMarker = found._marker;
            }

            // Scroll ke peta
            document.getElementById('map').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // ─── MAP SEARCH BAR ────────────────────────────────────────────────────────
        const mapSearchInput = document.getElementById('mapSearchInput');
        const mapSearchResults = document.getElementById('mapSearchResults');
        const clearMapSearch = document.getElementById('clearMapSearch');

        const avatarColors = {
            dikonfirmasi: 'background:#dcfce7;color:#16a34a',
            pending: 'background:#fef3c7;color:#d97706',
            meninggal: 'background:#f3f4f6;color:#6b7280',
        };

        mapSearchInput.addEventListener('input', function() {
            const q = this.value.trim().toLowerCase();

            clearMapSearch.classList.toggle('hidden', q.length === 0);

            if (q.length < 1) {
                mapSearchResults.classList.add('hidden');
                mapSearchResults.innerHTML = '';
                return;
            }

            const matches = allLansiaData.filter(d =>
                d.nama.toLowerCase().includes(q) ||
                (d.nik && d.nik.includes(q)) ||
                (d.desa && d.desa.toLowerCase().includes(q)) ||
                (d.kecamatan && d.kecamatan.toLowerCase().includes(q))
            ).slice(0, 8);

            if (matches.length === 0) {
                mapSearchResults.innerHTML = `
                <div class="px-4 py-3 text-sm text-gray-400 text-center">
                    <i class="fas fa-search-minus mr-1"></i> Tidak ditemukan
                </div>`;
                mapSearchResults.classList.remove('hidden');
                return;
            }

            mapSearchResults.innerHTML = matches.map(d => {
                const hasCoord = d.latitude && d.longitude;
                const avStyle = avatarColors[d.status] ?? 'background:#f3f4f6;color:#6b7280';
                return `
            <button type="button" class="map-search-result"
                data-id="${d.id}"
                data-lat="${d.latitude || ''}"
                data-lng="${d.longitude || ''}"
                data-name="${esc(d.nama)}"
                style="display:flex;align-items:center;gap:10px;width:100%;text-align:left;padding:10px 14px;border-bottom:1px solid #f3f4f6;background:white;cursor:pointer"
                onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='white'">
                <div style="width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;${avStyle}">
                    <i class="fas fa-user" style="font-size:11px"></i>
                </div>
                <div style="min-width:0;flex:1">
                    <p style="font-size:13px;font-weight:600;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${esc(d.nama)}</p>
                    <p style="font-size:11px;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${esc(d.desa ?? '')}${d.kecamatan ? ', '+esc(d.kecamatan) : ''}</p>
                </div>
                <i class="fas fa-map-marker-alt" style="font-size:11px;color:${hasCoord ? '#ef4444' : '#d1d5db'};flex-shrink:0"></i>
            </button>`;
            }).join('');

            // Event delegation untuk map search results
            mapSearchResults.addEventListener('click', function(e) {
                const btn = e.target.closest('.map-search-result');
                if (btn) {
                    const lat = parseFloat(btn.dataset.lat);
                    const lng = parseFloat(btn.dataset.lng);
                    const nama = btn.dataset.name;

                    mapSearchInput.value = nama;
                    mapSearchResults.classList.add('hidden');
                    clearMapSearch.classList.remove('hidden');

                    if (lat && lng) {
                        flyToLansia(lat, lng, nama);
                    } else {
                        alert(`Data "${nama}" belum memiliki koordinat.`);
                    }
                }
            });

            mapSearchResults.classList.remove('hidden');
        });

        function selectMapResult(id, lat, lng, nama) {
            mapSearchInput.value = nama;
            mapSearchResults.classList.add('hidden');
            clearMapSearch.classList.remove('hidden');

            if (lat && lng) {
                flyToLansia(lat, lng, nama);
            } else {
                alert(`Data "${nama}" belum memiliki koordinat.`);
            }
        }

        clearMapSearch.addEventListener('click', () => {
            mapSearchInput.value = '';
            clearMapSearch.classList.add('hidden');
            mapSearchResults.classList.add('hidden');
            map.flyTo([-8.58389, 116.3], 11, {
                duration: 1
            });
            if (activePopupMarker) activePopupMarker.closePopup();
        });

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', (e) => {
            if (!mapSearchInput.contains(e.target) && !mapSearchResults.contains(e.target)) {
                mapSearchResults.classList.add('hidden');
            }
        });



        // ─── TABLE FILTER (auto dengan debounce) ─────────────────────────────────
        const tableSearchInput = document.getElementById('tableSearchInput');
        const statusFilter = document.getElementById('statusFilter');
        const filterLoading = document.getElementById('filterLoading');
        let filterTimer = null;

        function doFilter() {
            const search = tableSearchInput.value.trim();
            const status = statusFilter.value;

            const params = new URLSearchParams(window.location.search);

            if (search) params.set('search', search);
            else params.delete('search');

            if (status) params.set('status', status);
            else params.delete('status');

            params.delete('page'); // reset ke page 1

            filterLoading.classList.remove('hidden');

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // Debounce 500ms saat ngetik
        tableSearchInput.addEventListener('input', function() {
            clearTimeout(filterTimer);
            filterLoading.classList.remove('hidden');
            filterTimer = setTimeout(doFilter, 500);
        });

        // Langsung filter saat ganti status
        statusFilter.addEventListener('change', doFilter);
    </script>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
        style="z-index: 9999;">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-semibold text-gray-900">Import Data Lansia</h2>
                <button id="closeImportModal"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            <form action="{{ route('lansia.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Format: .xlsx, .xls, atau .csv</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-5">
                    <p class="text-xs text-blue-700">
                        <strong>Tips:</strong> Pastikan file memiliki kolom:
                        Nama, NIK, Tgl Lahir, Umur, Alamat, Desa, Kecamatan, Kabupaten, RT, RW, Latitude, Longitude, Status
                    </p>
                </div>

                <div class="flex gap-2 justify-end">
                    <button type="button" id="cancelImportBtn"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Import modal
        const importModal = document.getElementById('importModal');
        document.getElementById('importBtn').addEventListener('click', () => importModal.classList.remove('hidden'));
        document.getElementById('closeImportModal').addEventListener('click', () => importModal.classList.add('hidden'));
        document.getElementById('cancelImportBtn').addEventListener('click', () => importModal.classList.add('hidden'));
        importModal.addEventListener('click', (e) => {
            if (e.target === importModal) importModal.classList.add('hidden');
        });
    </script>
@endsection
