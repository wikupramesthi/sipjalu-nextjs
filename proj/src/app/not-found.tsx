import type { Metadata } from "next";
import { NotFoundView } from "../views/NotFoundView";

export const metadata: Metadata = {
  title: "Halaman tidak ditemukan",
  robots: { index: false },
};

export default function GlobalNotFound() {
  return <NotFoundView />;
}
