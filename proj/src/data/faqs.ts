import type { FaqEntry } from "../types";

/** FAQ pusat bantuan — kategori mengikuti portal resmi; isi contoh/demo. */
export const faqCategories = [
  "Umum & Akses",
  "Data & Sinkronisasi",
  "Peserta Didik & GTK",
  "Rombongan Belajar",
  "Sarpras & Kurikulum",
  "Aplikasi & Bantuan",
] as const;

export const faqs: FaqEntry[] = [
  { id: "umum-1", category: "Umum & Akses", question: "Apa itu Data Pokok Pendidikan (Dapodik)?", answer: "Dapodik adalah sistem pendataan pendidikan nasional yang mencakup satuan pendidikan, peserta didik, pendidik, tenaga kependidikan, serta sarana dan prasarana. Halaman ini merupakan demonstrasi frontend, bukan layanan resmi Dapodik." },
  { id: "umum-2", category: "Umum & Akses", question: "Mengapa data Dapodik penting?", answer: "Data pokok pendidikan menjadi dasar perencanaan, bantuan operasional (misalnya BOS), pengadaan formasi guru, dan program pembinaan mutu. Data yang akurat menghasilkan keputusan yang tepat." },
  { id: "umum-3", category: "Umum & Akses", question: "Siapa yang wajib mengisi Dapodik?", answer: "Seluruh satuan pendidikan formal dan nonformal di bawah binaan Kemendikdasmen, diwakili operator yang ditetapkan pimpinan/dinas." },
  { id: "inst-1", category: "Aplikasi & Bantuan", question: "Di mana mengunduh aplikasi Dapodik?", answer: "Gunakan bagian Unduh Aplikasi pada demonstrasi ini untuk membuka portal Dapodik resmi. Demonstrasi tidak menyimpan atau membagikan installer." },
  { id: "inst-2", category: "Aplikasi & Bantuan", question: "Bagaimana urutan instalasi yang benar?", answer: "Unduh installer versi terbaru, jalankan instalasi utama, pasang patch sesuai urutan nomor, lalu login dengan akun yang terdaftar sebelum mulai transaksi data." },
  { id: "inst-3", category: "Aplikasi & Bantuan", question: "Apakah pembaruan versi menghapus data lama?", answer: "Tidak. Installer dan versi yang kompatibel menjaga struktur basis data lokal; selalu lakukan pencadangan sebelum pembaruan sebagai praktik aman." },
  { id: "akun-1", category: "Umum & Akses", question: "Bagaimana memperoleh akun Dapodik?", answer: "Akun dibuat oleh Kepala Sekolah melalui menu registrasi pada aplikasi dan diverifikasi oleh Dinas Pendidikan. Waspada penipuan: tidak ada layanan resmi yang meminta kata sandi." },
  { id: "akun-2", category: "Umum & Akses", question: "Saya lupa kata sandi, apa yang harus dilakukan?", answer: "Hubungi Admin Dapodik Dinas Pendidikan setempat untuk reset akun. Jangan membagikan kata sandi atau berkas autentikasi melalui tautan yang tidak resmi." },
  { id: "dik-1", category: "Peserta Didik & GTK", question: "Bagaimana menambah peserta didik baru?", answer: "Gunakan menu Peserta Didik > Tambah, isi kolom wajib sesuai dokumen identitas, dan gunakan fitur import untuk rombongan belajar agar lebih cepat serta minim salah ketik." },
  { id: "dik-2", category: "Peserta Didik & GTK", question: "Siswa pindah/keluar, bagaimana status penempatannya?", answer: "Gunakan fungsi mutasi atau keluar dengan menentukan tanggal efektif agar riwayat tetap tercatat dan agregat statistik semester akurat." },
  { id: "gtk-1", category: "Peserta Didik & GTK", question: "Bagaimana menambahkan penugasan guru?", answer: "Pastikan PTK terdaftar di referensi akun/verifikasi, lalu buka menu GTK > Penugasan pada satuan pendidikan terkait dan sesuaikan jam mengajar per rumpun." },
  { id: "gtk-2", category: "Peserta Didik & GTK", question: "Data GTK tidak muncul di verifikasi, kenapa?", answer: "Umumnya karena NIK/NPWP belum lolos validasi referensi. Perbaiki data pada aplikasi lalu sinkronisasi, dan ajukan perbaikan melalui verifikasi resmi." },
  { id: "sink-1", category: "Data & Sinkronisasi", question: "Kapan waktu terbaik sinkronisasi?", answer: "Lakukan setelah seluruh transaksi tervalidasi bersih saat jaringan stabil, di luar jam puncak bila memungkinkan, dan hindari memutus proses sebelum selesai." },
  { id: "sink-2", category: "Data & Sinkronisasi", question: "Sinkronisasi gagal, apa yang diperiksa?", answer: "Periksa koneksi, waktu (jadwal maintenance), dan riwayat validasi. Ulangi sinkronisasi, dan catat pesan error untuk disampaikan ke helpdesk resmi." },
  { id: "val-1", category: "Data & Sinkronisasi", question: "Apa maksud warna amber dan merah pada validasi?", answer: "Merah adalah error wajib (harus tuntas sebelum kirim), amber adalah peringatan yang tetap harus dipastikan kesesuaiannya dengan dokumen sumber." },
  { id: "val-2", category: "Data & Sinkronisasi", question: "Bagaimana memperbaiki validasi yang tidak dipahami?", answer: "Pelajari pesan validasi yang ditampilkan, buka menu Bantuan pada aplikasi atau panduan resmi, dan konsultasikan ke Admin Dapodik dinas bila diperlukan." },
  { id: "rombel-1", category: "Rombongan Belajar", question: "Bagaimana membuat rombongan belajar baru?", answer: "Buat rombongan belajar pada semester aktif, tentukan tingkat dan jenisnya, lalu tempatkan peserta didik serta wali kelas sesuai data sekolah." },
  { id: "rombel-2", category: "Rombongan Belajar", question: "Apa yang perlu diperiksa sebelum menyimpan pembagian kelas?", answer: "Pastikan peserta didik tidak terdaftar ganda, kapasitas kelas sesuai, dan penugasan wali kelas telah benar sebelum menyimpan perubahan." },
  { id: "sarpras-1", category: "Sarpras & Kurikulum", question: "Bagaimana memperbarui data sarana dan prasarana?", answer: "Periksa kondisi dan jumlah barang di setiap ruang, lalu perbarui data berdasarkan inventaris terbaru yang dimiliki satuan pendidikan." },
  { id: "sarpras-2", category: "Sarpras & Kurikulum", question: "Di mana memperbarui data kurikulum sekolah?", answer: "Pilih kurikulum yang digunakan pada data rombongan belajar dan pastikan penerapannya sesuai dengan dokumen kurikulum sekolah." },
];
