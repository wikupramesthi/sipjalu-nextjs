import type { Metadata } from "next";
import { ProgresView } from "../../views/ProgresView";

export const metadata: Metadata = {
  title: "Progres Pengiriman Data",
  description:
    "Progres pengiriman data satuan pendidikan per jenjang dan per wilayah (data contoh demonstrasi).",
  alternates: { canonical: "/progres/" },
};

export default function ProgresPage() {
  return <ProgresView />;
}
