import type { Metadata } from "next";
import { PanduanView } from "../../views/PanduanView";

export const metadata: Metadata = {
  title: "Panduan & Dokumentasi",
  description: "Katalog panduan aplikasi, petunjuk teknis, regulasi, dan materi pendukung Dapodik (tautan resmi).",
  alternates: { canonical: "/panduan/" },
};

export default function PanduanPage() {
  return <PanduanView />;
}
