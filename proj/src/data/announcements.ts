/** Konten ticker "Info Terkini" + dialog pengumuman (contoh demo). */
export type NoticeKind = "PENGUMUMAN" | "INFO";

export interface Notice {
  id: string;
  kind: NoticeKind;
  title: string;
  text: string;
  paragraphs?: string[];
  published: string;
}

export const notices: Notice[] = [
  {
    id: "pemeliharaan",
    kind: "INFO",
    title: "Pemberitahuan Pemeliharaan Layanan Dapodik",
    text: "Dalam rangka kestabilan layanan, saat ini sedang dilakukan pemeliharaan layanan sinkronisasi Dapodik. Selama proses berlangsung, layanan dapat mengalami perlambatan atau gangguan sementara. Satuan pendidikan diimbau melakukan sinkronisasi ulang secara berkala setelah proses selesai.",
    paragraphs: [
      "Dalam rangka kestabilan layanan, saat ini sedang dilakukan pemeliharaan layanan sinkronisasi Dapodik.",
      "Pelaksanaan optimasi dimulai pada:",
      "Jumat, 25 September 2026 pukul 20:00 WIB - Sabtu, 26 September 2026 pukul 08:00 WIB",
      "Selama proses berlangsung, layanan sinkronisasi Dapodik dapat mengalami perlambatan, gangguan, atau tidak dapat diakses sementara waktu.",
      "Kami mengimbau Satuan Pendidikan untuk menyesuaikan waktu pelaksanaan sinkronisasi dan melakukan percobaan kembali secara berkala setelah proses optimasi selesai.",
      "Mohon maaf atas ketidaknyamanan yang ditimbulkan. Terima kasih atas perhatian dan kerja sama Bapak/Ibu.",
    ],
    published: "25 September 2026",
  },
  {
    id: "waspada",
    kind: "PENGUMUMAN",
    title: "Waspada Informasi Mengatasnamakan Dapodik",
    text: "Waspada terhadap surat, pengumuman, atau informasi yang mengatasnamakan Dapodik maupun Kemendikdasmen. Pastikan keaslian informasi hanya melalui Portal Dapodik dan kanal resmi Kemendikdasmen.",
    published: "25 September 2026",
  },
  {
    id: "mutakhir",
    kind: "PENGUMUMAN",
    title: "Pemutakhiran Data Berkala",
    text: "Pastikan data satuan pendidikan, peserta didik, guru dan tenaga kependidikan, rombongan belajar, serta sarana prasarana selalu diperbarui secara berkala untuk mendukung penyelenggaraan layanan pendidikan yang berkualitas.",
    published: "20 September 2026",
  },
];
