import type { Metadata } from "next";
import { HomeView } from "../views/HomeView";

export const metadata: Metadata = {
  title: "Beranda — Data Pokok Pendidikan",
  description:
    "Demonstrasi portal Dapodik: potret pendidikan Indonesia, progres pengiriman data, berita, panduan, FAQ, dan unduhan (data contoh).",
  alternates: { canonical: "/" },
};

export default function HomePage() {
  return <HomeView />;
}
