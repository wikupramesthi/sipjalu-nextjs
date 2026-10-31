import type { Metadata } from "next";
import { SekolahView } from "../../views/SekolahView";

export const metadata: Metadata = {
  title: "Profil Sekolah",
  description: "Profil satuan pendidikan dari katalog contoh demonstrasi (query npsn 8 digit, divalidasi di sisi klien).",
  alternates: { canonical: "/sekolah/" },
  robots: { index: false }, // halaman ber-parameter, tidak perlu diindeks terpisah
};

export default function SekolahPage() {
  return <SekolahView />;
}
