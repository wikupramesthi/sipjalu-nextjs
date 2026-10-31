import type { PanduanDoc } from "../types";

/** Katalog dokumentasi (judul mengikuti portal resmi; tautan ke portal). */
export const panduanDocs: PanduanDoc[] = [
  { id: "manual-dapodikdasmen", category: "Panduan Aplikasi", title: "Manual Aplikasi Dapodikdasmen", type: "PDF", sizeMb: 3.2, version: "2027", description: "Petunjuk lengkap penggunaan aplikasi: instalasi, login, transaksi data, dan sinkronisasi.", target: "Operator Sekolah" },
  { id: "tombal-sd", category: "Panduan Aplikasi", title: "Tombol Validasi SD (TOMBAL)", type: "PDF", sizeMb: 2.4, version: "2.0", description: "Panduan mengisi dan memperbaiki pengisian SD-OK: daftar periksa validasi sekolah dasar.", target: "Operator SD" },
  { id: "panduan-si-paling", category: "Panduan Aplikasi", title: "Panduan Si Paling", type: "TIF", sizeMb: 1.1, version: "1.0", description: "Enam tombol penting validasi harian untuk menjaga kualitas data.", target: "Operator Sekolah" },
  { id: "juknis-tugas-tambahan", category: "Juknis", title: "Pemutakhiran Data Tugas Tambahan Kepala Sekolah", type: "Doc", sizeMb: 0.8, version: "2026", description: "Tata cara mengisi tugas tambahan kepala sekolah sebagai guru dan pengawas.", target: "Dinas & Sekolah" },
  { id: "panduan-sso", category: "Juknis", title: "Panduan Single Sign-On (SSO)", type: "PDF", sizeMb: 1.6, version: "2026", description: "Mekanisme login tunggal untuk layanan data pendidikan.", target: "Semua Pengguna" },
  { id: "panduan-pupns", category: "Regulasi", title: "Panduan Penetapan PUPNS", type: "PDF", sizeMb: 2.7, version: "2023", description: "Penetapan penggunaan nomor unik pegawai bagi pendidik dan tenaga kependidikan.", target: "Dinas Pendidikan" },
  { id: "panduan-pintasan", category: "Materi", title: "Pintasan Menu Aplikasi", type: "PDF", sizeMb: 0.5, version: "2027", description: "Ringkasan jalan pintas keyboard dan navigasi menu aplikasi.", target: "Operator Sekolah" },
  { id: "panduan-gasol", category: "Materi", title: "Gaspol — Daftar Isi dan Validasi", type: "PDF", sizeMb: 1.9, version: "2026", description: "Garis Besar Alur dan Standar Operasional Pengisian Laporan untuk petugas referensi.", target: "Operator Referensi" },
];
