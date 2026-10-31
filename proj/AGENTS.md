## Project background
Indonesian-language Dapodik-inspired frontend requested using Next.js and TypeScript. An independent demonstration for exploring school information, not an official government service.

## Product shape
Next.js uses .next for generated build output (gitignored).
Multi-page static portal with a forest-green and mint theme (replicated dapo home sections: ticker marquee + auto notice carousel, count-up hero tiles, jenjang table with Guru/Tendik/Status/TOTAL, 30-day sync chart with KPIs/tabs/tooltip/hover, pembaruan tiles, featured berita) (tokens in src/styles/tokens.css) (Next 16 App Router output:"export", trailingSlash) covering all
reference-portal routes: /, /berita(+slug SSG), /pencarian?q=, /sekolah?npsn=, /progres +
provinsi/kabupaten/kecamatan SSG drilldowns, /ikd + province/district, /panduan, /faq,
/unduh-aplikasi, /429, /404 via not-found+error boundaries, sitemap.ts/robots.ts metadata
routes. Architecture: src/views are framework-agnostic client pages shared by the Next app
(src/app thin per-route shells with generateMetadata + generateStaticParams, dynamicParams
false) and the Vite SPA artifact (src/main.tsx + src/lib/spa-routes path matcher); both get
navigation via NavContext (lib/nav.tsx; next/navigation only in src/app/nav-bridge.tsx).
Security: CSP/host headers in public/_headers + docs/SECURITY.md (connect-src none),
outbound link allow-list gov domains, csv.ts formula-injection guard, search/NPSN input
validation. Sample data only in src/data/*.ts; no backend. UI state (filters, accordion,
dialog, CSV export) client-side. Preview serves port 3000; run npm test for artifact+unit.
When adding routes: src/app + src/config/routes.ts + src/lib/spa-routes.tsx together.

<!-- BEGIN:nextjs-agent-rules -->

## This is NOT the Next.js you know

This version has breaking changes — APIs, conventions, and file structure may all differ from your training data. Read the relevant guide in `node_modules/next/dist/docs/` (resolved from this file's directory; in monorepos the `next` package may not be visible from the repo root) before writing any code. Heed deprecation notices.

This block is written and re-added by `next dev` — verify at `node_modules/next/dist/server/lib/generate-agent-files.js`. Removing it from a diff only re-creates the uncommitted change; committing it with your work keeps the tree clean.

<!-- END:nextjs-agent-rules -->
