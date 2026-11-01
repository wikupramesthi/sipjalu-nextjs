# Catatan Keamanan — Portal SIPJALU

Dokumen ini menjelaskan model ancaman dan mitigasi yang diterapkan agar aplikasi
layak dijalankan di lingkungan produksi, termasuk halaman FAQ yang membaca data
publik dari backend.

## 1. Model ancaman & mitigasi

| Ancaman | Status | Mitigasi di kode |
| --- | --- | --- |
| Stored/reflected XSS | Mitigasi | React escaping; `dangerouslySetInnerHTML` tidak dipakai; input dari URL dibersihkan (`sanitizeInput`) sebelum masuk DOM |
| CSV/spreadsheet formula injection (MHTML-style) | Mitigasi | `src/lib/csv.ts` membungkus sel yang diawali `= + - @` dengan kutip, membatasi panjang, quote escaping |
| Open redirect via `?next=`/link | Mitigasi | `safeInternalPath()` menolak `//host`, `safeExternalHref()` menolak host di luar allow-list gov |
| Clickjacking | Mitigasi | `X-Frame-Options: DENY` + CSP `frame-ancestors 'none'` |
| MIME sniffing | Mitigasi | `X-Content-Type-Options: nosniff` |
| Eksfiltrasi data klien | Mitigasi | FAQ hanya membaca endpoint API backend; CSP `connect-src` dibatasi ke origin backend dan link keluar tetap memakai allow-list |
| Pencurian kredensial | Tidak relevan | Tidak ada form login; dialog "Masuk ke Sistem" hanya membuka portal resmi (tanpa input apa pun) |
| Supply-chain (npm) | Proses | `package-lock.json` di-commit; `npm run audit` (high+) di CI; dependency minimal; `overrides` untuk transitive bermasalah |
| Information disclosure | Mitigasi | `poweredByHeader:false`, error boundary tanpa stack (demo), metadata robots noindex situs demo |
| Malformed percent-encoding di URL | Mitigasi | `decodeSegment()` fallback; rute tak dikenal → 404; NPSN regex 8 digit sebelum lookup |

## 2. Content Security Policy

Diterapkan di edge (statis tidak bisa mengirim header sendiri). Versi untuk artefak
Vite (`dist/`, tanpa inline-script):

```
Content-Security-Policy:
  default-src 'self';
  script-src 'self';
  style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
  font-src 'self' https://fonts.gstatic.com;
  img-src 'self' data:;
  connect-src 'self' https://api.portal.example.go.id;
  form-action 'self';
  base-uri 'none';
  object-src 'none';
  frame-ancestors 'none';
  upgrade-insecure-requests
```

Catatan ekspor Next.js (`npm run build:next`): injeksi payload flight berupa inline
script, sehingga bila artefak itu yang di-deploy, tambahkan `'unsafe-inline'` pada
`script-src` ATAU (disarankan) serve lewat server dengan nonce per-request:

```nginx
# Nginx contoh — root = out atau dist
server {
  listen 443 ssl http2;
  server_name portal.example.go.id;

  add_header Content-Security-Policy "default-src 'self'; script-src 'self' https: 'nonce-$cspnonce'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.portal.example.go.id; frame-ancestors 'none'; base-uri 'none'; object-src 'none'; upgrade-insecure-requests" always;
  add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
  add_header X-Content-Type-Options "nosniff" always;
  add_header X-Frame-Options "DENY" always;
  add_header Referrer-Policy "strict-origin-when-cross-origin" always;
  add_header Permissions-Policy "camera=(), microphone=(), geolocation=(), payment=(), usb=()" always;

  # aset bertanda hash boleh selamanya; HTML jangan
  location /_next/static/ { expires 1y; add_header Cache-Control "public, immutable"; }
  location /assets/       { expires 1y; add_header Cache-Control "public, immutable"; }
  location /              { try_files $uri $uri/index.html =404; add_header Cache-Control "no-cache"; }
}
```

## 3. Data flow & privasi

- Halaman FAQ membaca data publik aktif dari endpoint backend `/api/faqs`.
  Konfigurasikan `NEXT_PUBLIC_API_BASE_URL` untuk Next.js dan
  `VITE_API_BASE_URL` untuk build Vite. Endpoint API memakai HTTPS di produksi.
- Bila `PUBLIC_API_KEY` di backend diaktifkan, samakan dengan
  `NEXT_PUBLIC_API_KEY` atau `VITE_PUBLIC_API_KEY`. Kunci ini dibundel ke browser
  dan hanya berfungsi sebagai penghalang scraper, bukan autentikasi/rahasia.
- CSP `connect-src` di `public/_headers` memuat origin lokal untuk development.
  Saat deploy, ganti/tambahkan origin backend produksi yang persis; jangan
  memakai wildcard.
- Data lain yang belum terhubung tetap berasal dari `src/data/*`; tidak ada
  kredensial pengguna atau analitik yang dikirim ke backend.
- File CSV dibuat di memori browser (`URL.createObjectURL`) lalu blob direkvoke;
  tidak ada unggahan.
- Tidak ada kredensial pengguna atau data formulir yang dikirim ke API. Untuk
  integrasi API lain, perlakukan `src/data` sebagai anti-corruption layer: tulis
  normalizer dan jangan render payload mentah.

## 4. Checklist pra-rilis

- [ ] `npm run typecheck` dan `npm test` hijau (10 unit + 1 kontrak artefak)
- [ ] `npm run audit` bersih untuk dependency produksi
- [ ] Header CSP/di atas aktif di edge/CDN dan `connect-src` memuat origin API yang tepat (verifikasi `securityheaders.com`)
- [ ] `NEXT_PUBLIC_SITE_URL` menunjuk domain deploy (sitemap/robots canonical)
- [ ] Aset merek diganti/diizinkan; teks selalu menyandang status "demonstrasi"
- [ ] Build ulang: `npm run build:next` (atau `npm run build` untuk SPA Page)
- [ ] Deep-link 404 (`/sesuatu/` menampilkan halaman 404, bukan blank)
- [ ] Uji keyboard/Screen reader dasar: skip-link, dialog trap focus, kontras
