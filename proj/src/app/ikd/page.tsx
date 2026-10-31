import type { Metadata } from "next";
import { IkdView } from "../../views/IkdView";

export const metadata: Metadata = {
  title: "Indeks Kualitas Data",
  description: "Penjelasan dan agregat contoh Indeks Kualitas Data (IKD) pendidikan Indonesia.",
  alternates: { canonical: "/ikd/" },
};

export default function IkdPage() {
  return <IkdView />;
}
