{{-- Peta pemilih lokasi (Leaflet + OpenStreetMap).
     Otomatis mengisi input #lokasi, #latitude, #longitude yang sudah ada di halaman.
     Butuh koneksi internet untuk tile OSM & reverse geocode Nominatim. --}}

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    #aduan-map {
        height: 340px;
        border-radius: 12px;
        position: relative;
        z-index: 0;
    }

    .aduan-pin span {
        display: block;
        width: 26px;
        height: 26px;
        background: #dc3545;
        border: 3px solid #fff;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        box-shadow: 0 2px 6px rgba(0, 0, 0, .35);
    }
</style>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
            <div>
                <h5 class="fw-bold mb-1">Lokasi di Peta</h5>
                <p class="text-muted small mb-0">Klik peta atau geser pin untuk menentukan titik lokasi.</p>
            </div>
            <button type="button" id="btn-lokasi-saya" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-geo-alt-fill me-1"></i> Gunakan Lokasi Saya
            </button>
        </div>

        <div id="aduan-map" class="mt-3"></div>
        <small id="map-status" class="text-muted d-block mt-2">Memuat peta…</small>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusEl = document.getElementById('map-status');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const lokasiInput = document.getElementById('lokasi');

        const setStatus = (msg) => {
            if (statusEl) statusEl.textContent = msg;
        };

        if (typeof L === 'undefined') {
            setStatus('Peta gagal dimuat (CDN Leaflet tidak dapat diakses, periksa koneksi internet). Koordinat tetap bisa diisi manual.');
            return;
        }

        // Default: pusat Kota Bekasi
        const DEFAULT_POS = [-6.2383, 106.8525];
        const DEFAULT_ZOOM = 13;

        const parseCoord = (val) => {
            const n = parseFloat(String(val || '').replace(',', '.'));
            return Number.isFinite(n) ? n : null;
        };

        const startLat = parseCoord(latInput && latInput.value);
        const startLng = parseCoord(lngInput && lngInput.value);
        const hasStart = startLat !== null && startLat >= -90 && startLat <= 90 &&
            startLng !== null && startLng >= -180 && startLng <= 180;
        const startPos = hasStart ? [startLat, startLng] : DEFAULT_POS;

        const map = L.map('aduan-map').setView(startPos, hasStart ? 16 : DEFAULT_ZOOM);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
        }).addTo(map);

        const redIcon = L.divIcon({
            className: 'aduan-pin',
            html: '<span></span>',
            iconSize: [26, 26],
            iconAnchor: [13, 24]
        });

        const marker = L.marker(startPos, {
            draggable: true,
            icon: redIcon
        }).addTo(map);

        let reverseTimer = null;

        const reverseGeocode = (lat, lng) => {
            if (!lokasiInput) return;
            clearTimeout(reverseTimer);
            reverseTimer = setTimeout(() => {
                fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng + '&accept-language=id')
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.display_name) {
                            lokasiInput.value = data.display_name;
                            setStatus('Alamat terisi otomatis dari titik peta.');
                        }
                    })
                    .catch(() => {
                        setStatus('Titik tersimpan, tetapi alamat otomatis gagal dimuat.');
                    });
            }, 500);
        };

        const applyPosition = (lat, lng, opts) => {
            const options = Object.assign({
                reverse: true,
                pan: false
            }, opts || {});
            const fixedLat = Number(lat).toFixed(7);
            const fixedLng = Number(lng).toFixed(7);

            if (latInput) latInput.value = fixedLat;
            if (lngInput) lngInput.value = fixedLng;
            marker.setLatLng([lat, lng]);

            // Beri tahu form lain (mis. cek duplikat) bahwa koordinat berubah
            if (latInput) latInput.dispatchEvent(new Event('change', { bubbles: true }));
            if (lngInput) lngInput.dispatchEvent(new Event('change', { bubbles: true }));

            if (options.pan) map.flyTo([lat, lng], 16);

            if (options.reverse) {
                setStatus('Mencari alamat…');
                reverseGeocode(fixedLat, fixedLng);
            } else {
                setStatus('Titik lokasi: ' + fixedLat + ', ' + fixedLng);
            }
        };

        map.on('click', (e) => {
            applyPosition(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', () => {
            const pos = marker.getLatLng();
            applyPosition(pos.lat, pos.lng);
        });

        // Ketik koordinat manual -> peta mengikuti
        const syncFromInputs = () => {
            const la = parseCoord(latInput && latInput.value);
            const ln = parseCoord(lngInput && lngInput.value);
            if (la !== null && ln !== null && la >= -90 && la <= 90 && ln >= -180 && ln <= 180) {
                marker.setLatLng([la, ln]);
                map.panTo([la, ln]);
                setStatus('Titik lokasi: ' + la.toFixed(7) + ', ' + ln.toFixed(7));
            }
        };

        if (latInput) latInput.addEventListener('change', syncFromInputs);
        if (lngInput) lngInput.addEventListener('change', syncFromInputs);

        const requestBrowserLocation = (auto) => {
            if (!('geolocation' in navigator)) {
                if (!auto) setStatus('Perangkat/browser tidak mendukung geolocation. Klik peta untuk memilih titik.');
                else setStatus('Klik peta atau gunakan tombol "Gunakan Lokasi Saya".');
                return;
            }

            if (!auto) setStatus('Meminta akses lokasi…');

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    applyPosition(pos.coords.latitude, pos.coords.longitude, {
                        pan: true
                    });
                },
                (err) => {
                    if (err && err.code === 1) {
                        setStatus('Akses lokasi ditolak. Klik peta untuk memilih titik, atau izinkan akses lokasi lalu coba lagi.');
                    } else {
                        setStatus('Gagal mendapatkan lokasi. Klik peta untuk memilih titik.');
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000
                }
            );
        };

        const btn = document.getElementById('btn-lokasi-saya');
        if (btn) btn.addEventListener('click', () => requestBrowserLocation(false));

        if (hasStart) {
            setStatus('Titik lokasi: ' + startLat.toFixed(7) + ', ' + startLng.toFixed(7));
        } else {
            setStatus('Klik peta atau gunakan tombol "Gunakan Lokasi Saya".');
            // Minta akses lokasi otomatis saat halaman dibuka
            requestBrowserLocation(true);
        }
    });
</script>
