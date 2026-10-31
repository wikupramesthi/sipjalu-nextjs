/**
 * Central route registry — single source of truth for navigation, the SPA
 * matcher (Vite build), and sitemap generation. Keep in sync with src/app.
 */

export interface RouteDef {
  id: string;
  /** Path without trailing slash; ":param" marks a dynamic segment. */
  path: string;
  label: string;
  description: string;
  inHeader?: boolean;
  inFooter?: boolean;
}

export const routes = {
  home: { id: "home", path: "/", label: "Beranda", description: "Beranda Portal Dapodik — demonstrasi antarmuka.", inHeader: true, inFooter: true },
  progres: { id: "progres", path: "/progres", label: "Progres Pengiriman", description: "Progres pengiriman data per wilayah dan jenjang.", inHeader: true, inFooter: true },
  ikd: { id: "ikd", path: "/ikd", label: "IKD", description: "Indeks Kualitas Data pendidikan Indonesia.", inHeader: true, inFooter: true },
  unduh: { id: "unduh", path: "/unduh-aplikasi", label: "Unduh Aplikasi", description: "Unduh installer dan patch Aplikasi Dapodik.", inHeader: true, inFooter: true },
  berita: { id: "berita", path: "/berita", label: "Berita", description: "Berita dan informasi terkini seputar Dapodik.", inHeader: true, inFooter: true },
  faq: { id: "faq", path: "/faq", label: "FAQ", description: "Pusat bantuan dan pertanyaan umum Dapodik.", inHeader: true, inFooter: true },
  beritaDetail: { id: "beritaDetail", path: "/berita/:slug", label: "Detail Berita", description: "Detail artikel berita Dapodik." },
  panduan: { id: "panduan", path: "/panduan", label: "Panduan", description: "Panduan dan dokumentasi aplikasi Dapodik." },
  pencarian: { id: "pencarian", path: "/pencarian", label: "Pencarian", description: "Temukan data satuan pendidikan berdasarkan nama, NPSN, atau wilayah." },
  sekolah: { id: "sekolah", path: "/sekolah", label: "Profil Sekolah", description: "Profil satuan pendidikan (data contoh)." },
  throttled: { id: "throttled", path: "/429", label: "Terlalu Banyak Permintaan", description: "Layanan sementara sibuk." },
} satisfies Record<string, RouteDef>;

export interface MenuItem {
  id: string;
  path: string;
  label: string;
}

/**
 * Menu PPJ. Beberapa label sengaja berbagi halaman yang sudah ada
 * (Informasi PPJ/Regulasi → /panduan, Progres/Transparansi → /progres);
 * `id` unik dipakai sebagai React key dan penanda menu aktif.
 */
const menuItems: MenuItem[] = [
  { id: "home", path: "/", label: "Beranda" },
  { id: "informasi-ppj", path: "/panduan", label: "Informasi PPJ" },
  { id: "progres-pelaporan", path: "/progres", label: "Progres Pelaporan" },
  { id: "statistik-ppj", path: "/ikd", label: "Statistik PPJ" },
  { id: "transparansi-ppj", path: "/progres", label: "Transparansi PPJ" },
  { id: "regulasi", path: "/panduan", label: "Regulasi" },
  { id: "berita", path: "/berita", label: "Berita" },
  { id: "faq", path: "/faq", label: "FAQ" },
];

export const headerNav: MenuItem[] = [
  { id: "home", path: "/", label: "Beranda" },
  { id: "progres", path: "/progres", label: "Progres Pengiriman" },
  { id: "ikd", path: "/ikd", label: "IKD" },
  { id: "unduh", path: "/unduh-aplikasi", label: "Unduh Aplikasi" },
  { id: "berita", path: "/berita", label: "Berita" },
  { id: "faq", path: "/faq", label: "FAQ" },
];
export const footerNav: MenuItem[] = menuItems;

/** Static pages used for the sitemap; article & wilayah paths appended by sitemap.ts. */
export const sitemapPaths = [
  "/",
  "/berita/",
  "/pencarian/",
  "/progres/",
  "/ikd/",
  "/panduan/",
  "/faq/",
  "/unduh-aplikasi/",
];

/** Builds a concrete trailing-slash path from a base and encoded segments. */
export function buildPath(base: string, ...segments: (string | number | undefined)[]): string {
  const joined = [base, ...segments]
    .filter((s): s is string | number => s !== undefined && s !== null && String(s) !== "")
    .map((s) => encodeURIComponent(String(s)).replace(/%2F/gi, "/"))
    .join("/")
    .replace(/^\/+|\/+$/g, "");
  return joined ? `/${joined}/` : "/";
}
