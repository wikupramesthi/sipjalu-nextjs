import type { Metadata } from "next";
import { FaqView } from "../../views/FaqView";

export const metadata: Metadata = {
  title: { absolute: "Pusat Bantuan SIPJALU — Pengendalian Pajak PJU" },
  description: "Pusat informasi seputar data wajib pajak, pembayaran, potensi, tunggakan, dan pengendalian pajak PJU.",
  alternates: { canonical: "/faq/" },
};

export default function FaqPage() {
  return <FaqView />;
}
