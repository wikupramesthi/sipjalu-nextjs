import type { Metadata } from "next";
import { FaqView } from "../../views/FaqView";

export const metadata: Metadata = {
  title: "FAQ — Pusat Bantuan",
  description: "Pusat bantuan Dapodik: instalasi, akun, peserta didik, GTK, sinkronisasi, validasi (konten contoh demonstrasi).",
  alternates: { canonical: "/faq/" },
};

export default function FaqPage() {
  return <FaqView />;
}
