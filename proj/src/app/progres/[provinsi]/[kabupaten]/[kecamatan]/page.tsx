import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { ProgresView } from "../../../../../views/ProgresView";
import { findDistrict, findProvince, provinceProgress } from "../../../../../data/progress";

export const dynamicParams = false;

export function generateStaticParams() {
  return provinceProgress.flatMap((province) =>
    province.districts.flatMap((district) =>
      district.subdistricts.map((subdistrict) => ({
        provinsi: province.kode,
        kabupaten: district.kode,
        kecamatan: subdistrict.kode,
      })),
    ),
  );
}

interface PageProps {
  params: Promise<{ provinsi: string; kabupaten: string; kecamatan: string }>;
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { provinsi, kabupaten, kecamatan } = await params;
  const province = findProvince(provinsi);
  const district = province ? findDistrict(province, kabupaten) : undefined;
  const sub = district?.subdistricts.find((s) => s.kode === kecamatan);
  return {
    title: `Progres ${sub?.name ?? "Kecamatan"}`,
    description: `Ringkasan kiriman data kecamatan (contoh): ${sub?.name ?? "-"}, ${district?.name ?? "-"}, ${province?.name ?? "-"}.`,
    alternates: { canonical: `/progres/${provinsi}/${kabupaten}/${kecamatan}/` },
  };
}

export default async function ProgresKecamatanPage({ params }: PageProps) {
  const { provinsi, kabupaten, kecamatan } = await params;
  const province = findProvince(provinsi);
  const district = province ? findDistrict(province, kabupaten) : undefined;
  if (!province || !district || !district.subdistricts.some((s) => s.kode === kecamatan)) {
    notFound();
  }
  return <ProgresView provinsi={provinsi} kabupaten={kabupaten} kecamatan={kecamatan} />;
}
