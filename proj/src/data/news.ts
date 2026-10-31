import type { Article, NewsCategory } from "../types";

/**
 * Katalog berita contoh. Judul/tanggal/slug mengikuti struktur berita portal
 * referensi sebagai demo tampilan; isi body adalah teks demonstrasi, bukan
 * artikel resmi.
 */
export const articles: Article[] = [
  {
    slug: "kemendikdasmen-rilis-aplikasi-dapodik-versi-2027b",
    title: "Kemendikdasmen Rilis Aplikasi Dapodik versi 2027.b",
    excerpt:
      "Jakarta – Kementerian Pendidikan Dasar dan Menengah melalui Direktorat Jenderal Pendidikan Anak Usia Dini, Pendidikan Dasar, dan Pendidikan Nonformal dan Inform…",
    body: [
      "Pembaruan 2027.b menghadirkan penguatan validasi data, perbaikan performa sinkronisasi, serta penyesuaian formulir mengikuti kebijakan pendataan Semester Ganjil 2026/2027.",
      "Operator satuan pendidikan dianjurkan melakukan pencadangan basis data sebelum pembaruan, lalu mengunduh installer dan patch resmi melalui portal Dapodik. Panduan penginstalan tersedia pada menu Panduan.",
      "Catatan rilis memuat daftar perubahan lengkap, persyaratan versi patch, serta jadwal pemutakhiran data. Teks ini merupakan konten demonstrasi — informasi sebenarnya hanya pada portal resmi.",
    ],
    date: "28 Agustus 2026",
    isoDate: "2026-08-28",
    category: "Berita",
    author: "Tim Humas Dapodik",
    featured: true,
  },
  {
    slug: "pemutakhiran-data-pokok-pendidikan-semester-ganjil-tahun-ajaran-20262027-dimulai-melalui-aplikasi-dapodik-versi-2027",
    title:
      "Pemutakhiran Data Pokok Pendidikan Semester Ganjil Tahun Ajaran 2026/2027 Dimulai melalui Aplikasi Dapodik Versi 2027",
    excerpt:
      "Jakarta — Kementerian Pendidikan Dasar dan Menengah melalui Direktorat Jenderal Pendidikan A…",
    body: [
      "Pemutakhiran awal semester mencakup data induk peserta didik baru, kelulusan, mutasi, penugasan guru, serta kondisi sarana dan prasarana. Progres pengiriman dipantau melalui menu Progres Pengiriman.",
      "Sasaran mutakhir data nasional adalah 100% pengiriman pada bulan pertama semester. Dinas pendidikan melakukan konfirmasi dan pendampingan satuan pendidikan yang belum sinkron.",
      "Jadwal resmi, target per wilayah, dan instruksi teknis diumumkan melalui portal Dapodik dan kanal dinas masing-masing.",
    ],
    date: "14 Agustus 2026",
    isoDate: "2026-08-14",
    category: "Berita",
    author: "Kelompok Kerja Dapodik",
  },
  {
    slug: "kemendikdasmen-rilis-aplikasi-dapodik-versi-2027a",
    title: "Kemendikdasmen Rilis Aplikasi Dapodik versi 2027.a",
    excerpt:
      "Jakarta – Kementerian Pendidikan Dasar dan Menengah melalui Direktorat Jenderal Pendidikan Anak Usia…",
    body: [
      "Versi 2027.a menjadi rilis awal siklus pemutakhiran Semester Ganjil 2026/2027. Pembaruan berfokus pada kesiapan formulir semester baru dan perbaikan stabilitas aplikasi.",
      "Instalasi direkomendasikan melalui installer 2027 diikuti patch berurutan. Seluruh berkas hanya tersedia pada portal resmi.",
    ],
    date: "13 Agustus 2026",
    isoDate: "2026-08-13",
    category: "Berita",
    author: "Tim Humas Dapodik",
  },
  {
    slug: "pengisian-dan-penyebarluasan-instrumen-survei-rumah-pendidikan",
    title: "Pengisian dan Penyebarluasan Instrumen Survei Rumah Pendidikan",
    excerpt:
      "Dalam rangka mendukung pengembangan dan peningkatan kualitas layanan Platform Rumah Pendidikan sebag…",
    body: [
      "Survei Rumah Pendidikan menghimpun pengalaman pengguna layanan pendidikan digital. Instrumen diisi kepala sekolah dan operator melalui akun resmi yang terdaftar.",
      "Data survei digunakan untuk perbaikan fitur dan pelatihan. Demonstrasi ini hanya menampilkan ringkasan informasi, tanpa koneksi ke instrumen sebenarnya.",
    ],
    date: "4 Agustus 2026",
    isoDate: "2026-08-04",
    category: "Informasi",
    author: "Tim Rumah Pendidikan",
  },
  {
    slug: "kemendikdasmen-rilis-aplikasi-dapodik-versi-2027-untuk-mendukung-pemutakhiran-data-semester-ganjil-tahun-ajaran-20262027",
    title:
      "Kemendikdasmen Rilis Aplikasi Dapodik versi 2027 untuk Mendukung Pemutakhiran Data Semester Ganjil Tahun Ajaran 2026/2027",
    excerpt:
      "Jakarta – Kementerian Pendidikan Dasar dan Menengah melalui Direktorat Jenderal Pendidikan Anak …",
    body: [
      "Rilis besar 2027 menyesuaikan struktur data dengan kebutuhan pendataan tahun ajaran baru, termasuk penyaluran bantuan dan pembinaan mutu yang bersumber dari data pokok pendidikan.",
      "Satuan pendidikan disarankan memperbarui aplikasi sebelum memulai transaksi semester, memastikan validasi bersih, lalu melakukan sinkronisasi pertama.",
    ],
    date: "18 Juli 2026",
    isoDate: "2026-07-18",
    category: "Berita",
    author: "Tim Humas Dapodik",
  },
];

export const newsCategories: NewsCategory[] = ["Berita", "Pengumuman", "Informasi"];

export function findArticle(slug: string): Article | undefined {
  const normalized = slug.toLowerCase();
  return articles.find((article) => article.slug === normalized);
}
