import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { IkdView } from "../../../../views/IkdView";
import { findIkdDistrict, findIkdProvince, ikdDistricts } from "../../../../data/ikd";

export const dynamicParams = false;

export function generateStaticParams() {
  return ikdDistricts.map((district) => ({
    provinsi: district.parents?.slug ?? "",
    kabupaten: district.slug,
  }));
}

interface PageProps {
  params: Promise<{ provinsi: string; kabupaten: string }>;
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { provinsi, kabupaten } = await params;
  const province = findIkdProvince(provinsi);
  const district = province ? findIkdDistrict(province.slug, kabupaten) : undefined;
  return {
    title: `IKD ${district?.name ?? "Kabupaten"}`,
    description: `Skor dan komponen IKD contoh untuk ${district?.name ?? "kabupaten"}.`,
    alternates: { canonical: `/ikd/${provinsi}/${kabupaten}/` },
  };
}

export default async function IkdKabupatenPage({ params }: PageProps) {
  const { provinsi, kabupaten } = await params;
  const province = findIkdProvince(provinsi);
  if (!province || !findIkdDistrict(province.slug, kabupaten)) notFound();
  return <IkdView provinsi={provinsi} kabupaten={kabupaten} />;
}
