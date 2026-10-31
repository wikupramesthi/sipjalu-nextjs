import type { Metadata } from "next";
import { ThrottledView } from "../../views/ThrottledView";

export const metadata: Metadata = {
  title: "Terlalu Banyak Permintaan",
  description: "Layanan sementara sibuk — permintaan Anda dibatasi sementara (halaman status 429).",
  robots: { index: false },
};

export default function ThrottledPage() {
  return <ThrottledView />;
}
