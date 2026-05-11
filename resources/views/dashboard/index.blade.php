@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('title', 'Dashboard - GIS Lansia')

@section('content')
    <div class="space-y-5">

        {{-- ── STATS CARDS ────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Dikonfirmasi --}}
            <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_dikonfirmasi'] }}</p>
                    <p class="text-gray-500 text-sm mt-1 font-medium">Dikonfirmasi</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-check text-2xl text-blue-500"></i>
                </div>
            </div>
            {{-- Menunggu Konfirmasi --}}
            <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_pending'] }}</p>
                    <p class="text-gray-500 text-sm mt-1 font-medium">Menunggu Konfirmasi</p>
                </div>
                <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-2xl text-yellow-500"></i>
                </div>
            </div>
            {{-- Ditolak --}}
            <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_ditolak'] }}</p>
                    <p class="text-gray-500 text-sm mt-1 font-medium">Ditolak</p>
                </div>
                <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-times text-2xl text-red-400"></i>
                </div>
            </div>

            {{-- Meninggal --}}
            <div class="bg-white rounded-xl shadow p-5 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_meninggal'] }}</p>
                    <p class="text-gray-500 text-sm mt-1 font-medium">Meninggal</p>
                </div>
                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-heart-broken text-2xl text-gray-400"></i>
                </div>
            </div>

        </div>

        {{-- ── PETA FULL WIDTH ─────────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow overflow-hidden" style="height: calc(100vh - 260px); min-height: 420px;">
            <div class="relative w-full h-full">

                {{-- Map --}}
                <div id="dashMap" class="w-full h-full"></div>

                {{-- Search bar pojok kiri atas --}}
                <div class="absolute top-3 left-3" style="width: 280px; z-index: 10000;">
                    <div class="relative">
                        <div class="flex items-center bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                            <input type="text" id="dashMapSearch" placeholder="Cari nama lansia..." autocomplete="off"
                                class="w-full px-3 py-2 text-sm text-gray-800 focus:outline-none placeholder-gray-400">
                            <button id="clearDashSearch"
                                class="hidden px-3 text-gray-400 hover:text-gray-600 border-l border-gray-200 py-2">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                            <button type="button" id="triggerSearch"
                                class="px-3 py-2 text-gray-400 hover:text-gray-600 border-l border-gray-200 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-search text-sm"></i>
                            </button>
                        </div>

                        {{-- Dropdown hasil pencarian --}}
                        <div id="dashSearchResults"
                            class="hidden absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden"
                            style="max-height: 240px; overflow-y: auto;">
                        </div>
                    </div>
                </div>

                {{-- Legenda pojok kiri bawah --}}
                <div
                    class="absolute bottom-6 left-3 bg-white rounded-lg shadow-md px-3 py-2 border border-gray-100" style="z-index: 10000;">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Keterangan</p>
                    <div class="flex items-center gap-2 text-xs text-gray-600 mb-1">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-500 shrink-0"></span>
                        <span>Aktif</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-600">
                        <span class="inline-block w-3 h-3 rounded-full bg-gray-400 shrink-0"></span>
                        <span>Non Aktif</span>
                    </div>
                </div>

                {{-- Info jumlah titik pojok kanan bawah --}}
                <div
                    class="absolute bottom-6 right-3 bg-white rounded-lg shadow-md px-3 py-2 border border-gray-100 text-xs text-gray-600" style="z-index: 10000;">
                    <i class="fas fa-map-pin text-red-500 mr-1"></i>
                    <strong>{{ $stats['total_berkoordinat'] }}</strong> titik ditampilkan
                </div>

            </div>
        </div>

    </div>

    <script>
        // ── DATA ─────────────────────────────────────────────────────────────────────
        const allLansiaData = @json($allLansiaForMap);
        console.log('📊 Dashboard Search Data:', allLansiaData.length, 'lansia berhasil dimuat');
        console.log('Contoh data:', allLansiaData.slice(0, 3));

        // ── INIT MAP ──────────────────────────────────────────────────────────────────
        let dashMap;
        let activeMarker = null;

        function initMapWhenReady() {
            if (typeof L === 'undefined') {
                console.warn('⏳ Leaflet library not ready yet, retrying...');
                setTimeout(initMapWhenReady, 100);
                return;
            }

            if (typeof L.markerClusterGroup !== 'function') {
                console.warn('⏳ MarkerCluster library not ready yet, retrying...');
                setTimeout(initMapWhenReady, 100);
                return;
            }

            try {
                console.log('✅ Libraries ready, initializing map...');
                dashMap = L.map('dashMap', {
                    zoomControl: false,
                    attributionControl: true,
                }).setView([-8.58389, 116.3], 11);
                console.log('✅ Map instance created:', dashMap);

                // Zoom control di kanan atas
                L.control.zoom({
                    position: 'topright'
                }).addTo(dashMap);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
                    maxZoom: 19,
                }).addTo(dashMap);

                function clusterIcon(cluster) {
                    var count = cluster.getChildCount();
                    var size = count < 10 ? 36 : count < 100 ? 42 : 48;
                    var fs = count < 100 ? 13 : 11;
                    return L.divIcon({
                        className: 'custom-cluster',
                        html: '<div class="custom-cluster-inner" style="width:' + size + 'px;height:' + size +
                            'px;font-size:' + fs + 'px">' + count + '</div>',
                        iconSize: [size, size],
                        iconAnchor: [size / 2, size / 2],
                    });
                }

                function markerIcon() {
                    return L.divIcon({
                        className: '',
                        html: '<div style="width:13px;height:13px;background:#ef4444;border:2.5px solid #fff;border-radius:50%;box-shadow:0 1px 5px rgba(0,0,0,0.4);outline:2px solid #dc2626"></div>',
                        iconSize: [13, 13],
                        iconAnchor: [6, 6],
                        popupAnchor: [0, -8],
                    });
                }

                function escD(s) {
                    return escHtml(s);
                }

                var clusterGroup = L.markerClusterGroup({
                    iconCreateFunction: clusterIcon,
                    maxClusterRadius: 60,
                    spiderfyOnMaxZoom: false,
                    showCoverageOnHover: false,
                    zoomToBoundsOnClick: false,
                    animate: true,
                });

                allLansiaData.forEach(function(item) {
                    if (!item.latitude || !item.longitude) return;

                    var marker = L.marker(
                        [parseFloat(item.latitude), parseFloat(item.longitude)], {
                            icon: markerIcon()
                        }
                    );

                    marker.bindPopup(
                        '<div style="font-family:sans-serif;min-width:140px">' +
                        '<p style="font-weight:700;font-size:13px;color:#111;margin:0 0 3px">' + escD(item
                        .nama) + '</p>' +
                        '<p style="font-size:11px;color:#6b7280;margin:0">NIK: ' + escD(item.nik) + '</p>' +
                        '</div>', {
                            maxWidth: 200
                        }
                    );

                    marker._lansiaData = item;
                    item._marker = marker;
                    clusterGroup.addLayer(marker);
                });

                clusterGroup.on('clusterclick', function(e) {
                    var markers = e.layer.getAllChildMarkers();
                    var count = markers.length;
                    var latlng = e.layer.getLatLng();

                    var content =
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
                        .openOn(dashMap);

                    L.DomEvent.stopPropagation(e);
                });

                dashMap.addLayer(clusterGroup);
                console.log('✅ Map initialized successfully with', allLansiaData.length, 'markers');
            } catch (error) {
                console.error('❌ Error initializing map:', error);
            }
        }

        // Start initialization after a short delay to ensure libraries are loaded
        setTimeout(initMapWhenReady, 500);
        const searchInput = document.getElementById('dashMapSearch');
        const searchResults = document.getElementById('dashSearchResults');
        const clearBtn = document.getElementById('clearDashSearch');
        let searchTimer = null;

        // Fungsi untuk jalankan search
        function doSearch() {
            const q = searchInput.value.trim().toLowerCase();

            clearBtn.classList.toggle('hidden', q.length === 0);

            if (q.length < 1) {
                searchResults.classList.add('hidden');
                searchResults.innerHTML = '';
                return;
            }

            const matches = allLansiaData.filter(function(d) {
                return d.nama.toLowerCase().includes(q) ||
                    (d.nik && d.nik.includes(q)) ||
                    (d.desa && d.desa.toLowerCase().includes(q)) ||
                    (d.kecamatan && d.kecamatan.toLowerCase().includes(q));
            }).slice(0, 8);

            if (matches.length === 0) {
                searchResults.innerHTML =
                    '<div style="padding:12px;text-align:center;color:#9ca3af;font-size:13px">' +
                    '<i class="fas fa-search" style="margin-right:4px"></i>Tidak ditemukan</div>';
                searchResults.classList.remove('hidden');
                return;
            }

            searchResults.innerHTML = matches.map(function(d) {
                var hasCoord = d.latitude && d.longitude;
                var bgAvatar = 'background:#fee2e2;color:#dc2626';
                return '<button type="button" class="search-result-item" ' +
                    'data-lat="' + (d.latitude || '') + '" ' +
                    'data-lng="' + (d.longitude || '') + '" ' +
                    'data-name="' + escHtml(d.nama) + '" ' +
                    'style="display:flex;align-items:center;gap:10px;width:100%;text-align:left;padding:10px 14px;border-bottom:1px solid #f3f4f6;background:white;cursor:pointer;transition:background .15s" ' +
                    'onmouseover="this.style.background=\'#eff6ff\'" onmouseout="this.style.background=\'white\'">' +
                    '<div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;' +
                    bgAvatar + '">' +
                    '<i class="fas fa-user" style="font-size:11px"></i></div>' +
                    '<div style="min-width:0;flex:1">' +
                    '<p style="font-size:13px;font-weight:600;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' +
                    escHtml(d.nama) + '</p>' +
                    '<p style="font-size:11px;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' +
                    escHtml(d.desa || '') + (d.kecamatan ? ', ' + escHtml(d.kecamatan) : '') + '</p></div>' +
                    '<i class="fas fa-map-marker-alt" style="font-size:11px;color:' + (hasCoord ? '#ef4444' :
                        '#d1d5db') + ';flex-shrink:0"></i>' +
                    '</button>';
            }).join('');

            // Event delegation untuk search results
            searchResults.addEventListener('click', function(e) {
                var btn = e.target.closest('.search-result-item');
                if (btn) {
                    var lat = parseFloat(btn.dataset.lat);
                    var lng = parseFloat(btn.dataset.lng);
                    var nama = btn.dataset.name;

                    searchInput.value = nama;
                    searchResults.classList.add('hidden');
                    clearBtn.classList.remove('hidden');

                    if (lat && lng) {
                        dashMap.flyTo([lat, lng], 16, { duration: 1.2 });
                        if (activeMarker) activeMarker.closePopup();

                        var found = allLansiaData.find(function(d) {
                            return Math.abs(parseFloat(d.latitude) - lat) < 0.00001 &&
                                Math.abs(parseFloat(d.longitude) - lng) < 0.00001;
                        });
                        if (found && found._marker) {
                            setTimeout(function() {
                                found._marker.openPopup();
                            }, 1300);
                            activeMarker = found._marker;
                        }
                    } else {
                        alert('"' + nama + '" belum memiliki koordinat.');
                    }
                }
            });

            searchResults.classList.remove('hidden');
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            // Jalankan search langsung
            doSearch();
        });

        // Tombol search icon
        document.getElementById('triggerSearch').addEventListener('click', function(e) {
            e.preventDefault();
            doSearch();
            searchInput.focus();
        });

        function pickResult(lat, lng, nama) {
            searchInput.value = nama;
            searchResults.classList.add('hidden');
            clearBtn.classList.remove('hidden');

            if (!lat || !lng) {
                alert('"' + nama + '" belum memiliki koordinat.');
                return;
            }

            dashMap.flyTo([lat, lng], 16, {
                duration: 1.2
            });

            if (activeMarker) activeMarker.closePopup();

            var found = allLansiaData.find(function(d) {
                return Math.abs(parseFloat(d.latitude) - lat) < 0.00001 &&
                    Math.abs(parseFloat(d.longitude) - lng) < 0.00001;
            });
            if (found && found._marker) {
                setTimeout(function() {
                    found._marker.openPopup();
                }, 1300);
                activeMarker = found._marker;
            }
        }

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchResults.innerHTML = '';
            searchResults.classList.add('hidden');
            clearBtn.classList.add('hidden');
            if (activeMarker) activeMarker.closePopup();
            dashMap.flyTo([-8.58389, 116.3], 11, {
                duration: 1
            });
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        function escHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        // alias lama tetap ada supaya tidak error
        function esc(str) {
            return escHtml(str);
        }
    </script>
@endsection
