import type { Metadata } from "next";
import { Suspense } from "react";
import { PencarianViewClient } from "../../views/PencarianViewClient";

export const metadata: Metadata = {
  title: "Pencarian Sekolah",
  description: "Halaman hasil pencarian satuan pendidikan berdasarkan nama, NPSN, atau wilayah (data contoh).",
  alternates: { canonical: "/pencarian/" },
};

export default function PencarianPage() {
  return <Suspense fallback={<div className="sj-state-page sj-container">Menyiapkan pencarian…</div>}>
    <PencarianViewClient />
  </Suspense>;
}
