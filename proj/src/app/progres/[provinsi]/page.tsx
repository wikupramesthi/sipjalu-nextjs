import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { ProgresView } from "../../../views/ProgresView";
import { findProvince, provinceProgress } from "../../../data/progress";

export const dynamicParams = false;

export function generateStaticParams() {
  return provinceProgress.map((p) => ({ provinsi: p.kode }));
}

interface PageProps {
  params: Promise<{ provinsi: string }>;
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { provinsi } = await params;
  const province = findProvince(provinsi);
  return {
    title: `Progres Pengiriman ${province?.name ?? "Provinsi"}`,
    description: `Progres pengiriman data contoh untuk tingkat provinsi (${province?.name ?? "?"}).`,
    alternates: { canonical: `/progres/${provinsi}/` },
  };
}

export default async function ProgresProvinsiPage({ params }: PageProps) {
  const { provinsi } = await params;
  if (!findProvince(provinsi)) notFound();
  return <ProgresView provinsi={provinsi} />;
}
