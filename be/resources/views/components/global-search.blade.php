{{-- Palette pencarian global admin (Ctrl+K) --}}
<div class="modal fade" id="globalSearchModal" tabindex="-1" aria-labelledby="globalSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" id="globalSearchInput" class="form-control border-0 shadow-none"
                        placeholder="Cari aduan, agenda, dokumen, halaman, event, pengguna… (min. 2 huruf)" autocomplete="off">
                    <kbd class="d-none d-md-inline-block">ESC</kbd>
                </div>
                <div id="globalSearchResults" class="p-2" style="max-height: 420px; overflow-y: auto;">
                    <p class="text-muted small mb-0 px-2 py-3 text-center">Ketik untuk mencari di seluruh modul.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const modalEl = document.getElementById('globalSearchModal');
        const input = document.getElementById('globalSearchInput');
        const results = document.getElementById('globalSearchResults');
        let timer = null;
        let firstUrl = null;

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[c]));
        }

        function render(groups) {
            firstUrl = null;

            if (!groups || groups.length === 0) {
                results.innerHTML = '<p class="text-muted small mb-0 px-2 py-3 text-center">Tidak ditemukan. Coba kata kunci lain.</p>';
                return;
            }

            let html = '';
            groups.forEach(g => {
                html += '<div class="px-2 pt-2 pb-1 small fw-bold text-muted text-uppercase"><i class="bi ' + esc(g.icon) + ' me-1"></i>' + esc(g.label) + '</div>';
                html += '<div class="list-group list-group-flush mb-1">';
                g.items.forEach((it, idx) => {
                    if (!firstUrl && it.url) firstUrl = it.url;
                    html += '<a href="' + esc(it.url) + '" class="list-group-item list-group-item-action py-2">'
                        + '<div class="fw-semibold small text-truncate">' + esc(it.title) + '</div>'
                        + '<div class="text-muted" style="font-size:11px;">' + esc(it.subtitle) + '</div>'
                        + '</a>';
                });
                html += '</div>';
            });
            results.innerHTML = html;
        }

        function doSearch() {
            const q = input.value.trim();
            if (q.length < 2) {
                results.innerHTML = '<p class="text-muted small mb-0 px-2 py-3 text-center">Ketik untuk mencari di seluruh modul.</p>';
                firstUrl = null;
                return;
            }
            fetch("{{ route('global.search') }}?q=" + encodeURIComponent(q), {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(res => render(res.data || []))
                .catch(() => {
                    results.innerHTML = '<p class="text-danger small mb-0 px-2 py-3 text-center">Pencarian gagal. Coba lagi.</p>';
                });
        }

        if (input) {
            input.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(doSearch, 350);
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && firstUrl) {
                    window.location.href = firstUrl;
                }
            });
        }

        if (modalEl) {
            modalEl.addEventListener('shown.bs.modal', () => {
                if (input) {
                    input.value = '';
                    results.innerHTML = '<p class="text-muted small mb-0 px-2 py-3 text-center">Ketik untuk mencari di seluruh modul.</p>';
                    input.focus();
                }
            });
        }

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                const m = bootstrap.Modal.getOrCreateInstance(modalEl);
                m.show();
            }
        });
    })();
</script>
