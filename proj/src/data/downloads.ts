import type { DownloadItem } from "../types";
import { OFFICIAL, SITE } from "./site";

/**
 * Download catalogue (descriptive demo values only). Every button links to
 * the official portal — this app never hosts binaries, which is the safest
 * posture for a static demo and is enforced by `safeExternalHref`.
 */
export const downloadItems: DownloadItem[] = [
  {
    id: "installer",
    label: "Installer Aplikasi Dapodik",
    kind: "installer",
    version: SITE.appVersion,
    size: "±150 MB",
    updated: "20 September 2026",
    description: "Paket instalasi utama untuk komputer operator satuan pendidikan.",
    href: OFFICIAL.portal,
    requiredFor: "Instalasi baru",
  },
  {
    id: "patch",
    label: `Dapodik ${SITE.appVersion} Patch`,
    kind: "patch",
    version: "2027.1.1",
    size: "±15 MB",
    updated: "20 September 2026",
    description: "Pembaruan minor; pasang berurutan dari nomor patch terlama sebelum digunakan.",
    href: OFFICIAL.portal,
    requiredFor: "Pasca instalasi",
  },
  {
    id: "runtime",
    label: "Runtime & Prasyarat Sistem",
    kind: "driver",
    version: "Visual C++ / .NET",
    size: "±80 MB",
    updated: "1 Agustus 2026",
    description: "Komponen pendukung yang diperlukan agar aplikasi berjalan stabil.",
    href: OFFICIAL.portal,
  },
  {
    id: "manual-unduhan",
    label: "Manual Aplikasi (Panduan)",
    kind: "manual",
    version: "2027",
    size: "±4 MB",
    updated: "1 Juli 2026",
    description: "Buku panduan penggunaan aplikasi — tersedia juga pada menu Panduan.",
    href: OFFICIAL.portal,
  },
];

export const systemRequirements = [
  { label: "Sistem Operasi", value: "Windows 10/11 64-bit" },
  { label: "Ruang Penyimpanan", value: "minimal 10 GB kosong" },
  { label: "RAM", value: "rekomendasi 8 GB" },
  { label: "Jaringan", value: "koneksi stabil saat sinkronisasi" },
  { label: "Peramban", value: "versi terkini (Chrome/Edge/Firefox)" },
  { label: "Akun", value: "dibuat Kepala Sekolah, divalidasi Dinas" },
];
