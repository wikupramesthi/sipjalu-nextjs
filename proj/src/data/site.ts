/** Global site constants — branding, disclaimer, services & external entry points. */

export const SITE = {
  title: "Portal Dapodik — Demonstrasi Antarmuka",
  shortTitle: "Data Pokok Pendidikan",
  owner: "Kementerian Pendidikan Dasar dan Menengah RI",
  tagline: "Akurat, Mutakhir dan Berkelanjutan.",
  heroEyebrow: "Portal Resmi Informasi Data Pokok Pendidikan",
  heroDescription:
    "Platform informasi dan pengendalian Pajak Penerangan Jalan Umum yang terintegrasi untuk mendukung pemantauan, pengawasan, dan optimalisasi penerimaan pajak secara transparan dan akuntabel.",
  description:
    "Demonstrasi portal Data Pokok Pendidikan dengan Next.js + TypeScript. Semua data adalah contoh; bukan layanan resmi pemerintah.",
  demoNotice:
    "Demonstrasi frontend. Bukan situs resmi pemerintah — tidak terhubung ke sistem Dapodik, tidak mengumpulkan kredensial, dan tidak membagikan installer.",
  appVersion: "2027.b",
  academicYear: "2026/2027",
  semester: "Ganjil",
  /** Tanggal "Pembaruan terakhir" pada hero (format ISO; ditampilkan locale id-ID). */
  lastSync: "2026-10-29",
  tableSource: "Sumber: Rekap Dapodik Kemendikdasmen RI (2026/2027 Semester Ganjil)",
  chartSource: "Sumber: Sistem Dapodik Kemendikdasmen RI",
} as const;

/** Only allow-listed government endpoints leave this application. */
export const OFFICIAL = {
  portal: "https://dapo.kemendikdasmen.go.id/",
  referensi: "https://referensi.data.kemendikdasmen.go.id/",
  verifier: "https://verifier.data.kemendikdasmen.go.id/",
  tanya: "https://tanya.data.kemendikdasmen.go.id/",
  instagram: "https://www.instagram.com/dapodik_official",
  youtube: "https://www.youtube.com/@DitjenPAUDDikdasPNFI",
} as const;

export const CONTACT = {
  email: "dapo@kemendikdasmen.go.id",
  hotline: "177",
  domain: "dapo.kemendikdasmen.go.id",
} as const;

export const SEMESTERS = ["2026/2027 Ganjil", "2025/2026 Genap"] as const;
export type SemesterLabel = (typeof SEMESTERS)[number];

/** Headline summary cards on "Dapodik dalam Angka". */
export const SUMMARY_STATS = [
  { label: "Jumlah Sekolah", value: "447.812", growth: "0.3%", trend: "up", tone: "blue", icon: "school" },
  { label: "Peserta Didik", value: "53.1 Jt", growth: "0.7%", trend: "up", tone: "cyan", icon: "graduate" },
  { label: "Guru & Tendik", value: "4.4 Jt", growth: "0.7%", trend: "down", tone: "violet", icon: "users" },
  { label: "Pengiriman Data", value: "97.3%", growth: "1.8%", trend: "up", tone: "green", icon: "chart" },
] as const;

/** Tiles di sisi kanan hero (kolom pertama dapo). */
export const HERO_STATS = [
  { label: "Total Sekolah", value: 447812, format: "thousands" },
  { label: "Peserta Didik", value: 53127371, format: "compact" },
  { label: "Guru & Tendik", value: 4412604, format: "compact" },
] as const;
