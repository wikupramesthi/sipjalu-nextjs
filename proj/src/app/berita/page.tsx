import type { Metadata } from "next";
import { BeritaListView } from "../../views/BeritaListView";

export const metadata: Metadata = {
  title: "Berita & Informasi",
  description: "Daftar berita dan pengumuman contoh seputar Dapodik untuk keperluan demonstrasi antarmuka.",
  alternates: { canonical: "/berita/" },
};

export default function BeritaPage() {
  return <BeritaListView />;
}
