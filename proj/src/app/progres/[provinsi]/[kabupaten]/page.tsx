import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { ProgresView } from "../../../../views/ProgresView";
import { findDistrict, findProvince, provinceProgress } from "../../../../data/progress";

export const dynamicParams = false;

export function generateStaticParams() {
  return provinceProgress.flatMap((province) =>
    province.districts.map((district) => ({ provinsi: province.kode, kabupaten: district.kode })),
  );
}

interface PageProps {
  params: Promise<{ provinsi: string; kabupaten: string }>;
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { provinsi, kabupaten } = await params;
  const province = findProvince(provinsi);
  const district = province ? findDistrict(province, kabupaten) : undefined;
  return {
    title: `Progres ${district?.name ?? "Kabupaten"}`,
    description: `Rincian kecamatan (contoh) di ${district?.name ?? "kabupaten"}, ${province?.name ?? ""}.`,
    alternates: { canonical: `/progres/${provinsi}/${kabupaten}/` },
  };
}

export default async function ProgresKabupatenPage({ params }: PageProps) {
  const { provinsi, kabupaten } = await params;
  const province = findProvince(provinsi);
  if (!province || !findDistrict(province, kabupaten)) notFound();
  return <ProgresView provinsi={provinsi} kabupaten={kabupaten} />;
}
