import type { Metadata } from "next";
import { UnduhView } from "../../views/UnduhView";

export const metadata: Metadata = {
  title: "Unduh Aplikasi Dapodik",
  description: "Katalog installer dan patch Aplikasi Dapodik beserta langkah instalasi — seluruh tautan menuju portal resmi.",
  alternates: { canonical: "/unduh-aplikasi/" },
};

export default function UnduhPage() {
  return <UnduhView />;
}
