import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { IkdView } from "../../../views/IkdView";
import { findIkdProvince, ikdProvinces } from "../../../data/ikd";

export const dynamicParams = false;

export function generateStaticParams() {
  return ikdProvinces.map((province) => ({ provinsi: province.slug }));
}

interface PageProps {
  params: Promise<{ provinsi: string }>;
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { provinsi } = await params;
  const province = findIkdProvince(provinsi);
  return {
    title: `IKD ${province?.name ?? "Provinsi"}`,
    description: `Komponen skor IKD contoh untuk ${province?.name ?? "provinsi"} beserta kabupaten/kota pendamping.`,
    alternates: { canonical: `/ikd/${provinsi}/` },
  };
}

export default async function IkdProvinsiPage({ params }: PageProps) {
  const { provinsi } = await params;
  if (!findIkdProvince(provinsi)) notFound();
  return <IkdView provinsi={provinsi} />;
}
