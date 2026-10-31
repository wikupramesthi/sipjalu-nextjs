import type { IkdRegion, IkdScore } from "../types";

export const IKD_DEFINITIONS = [
  { label: "Kelengkapan", description: "Kecakapan pengisian seluruh isian wajib satuan pendidikan.", weight: "40%" },
  { label: "Validitas", description: "Kesesuaian data dengan kaidah, referensi, dan dokumen sumber.", weight: "35%" },
  { label: "Ketepatan Waktu", description: "Kecapaian pemutakhiran sesuai kalender semester.", weight: "25%" },
] as const;

/** Composite = weighted average of components (rounded to 0.1). */
export function computeIkd(kelengkapan: number, validitas: number, waktu: number): IkdScore {
  const components = [
    { label: "Kelengkapan", description: "Kecakapan pengisian seluruh isian wajib satuan pendidikan.", score: kelengkapan, weight: "40%" },
    { label: "Validitas", description: "Kesesuaian data dengan kaidah, referensi, dan dokumen sumber.", score: validitas, weight: "35%" },
    { label: "Ketepatan Waktu", description: "Kecapaian pemutakhiran sesuai kalender semester.", score: waktu, weight: "25%" },
  ];
  const composite = Math.round((kelengkapan * 0.4 + validitas * 0.35 + waktu * 0.25) * 10) / 10;
  return { composite, trend: 0, components };
}

export const nationalIkd: IkdRegion = {
  slug: "nasional",
  kode: "00",
  name: "Indonesia",
  level: "provinsi",
  total: 447812,
  score: {
    ...computeIkd(94.1, 96.8, 92.5),
    trend: 1.4,
  },
};

export const ikdProvinces: IkdRegion[] = [
  { slug: "nanggroe-aceh-darussalam", kode: "11", name: "Nanggroe Aceh Darussalam", level: "provinsi", total: 5210, score: { ...computeIkd(90.2, 94.1, 88.7), trend: 0.8 } },
  { slug: "sumatera-utara", kode: "12", name: "Sumatera Utara", level: "provinsi", total: 22140, score: { ...computeIkd(92.7, 95.2, 90.4), trend: 1.1 } },
  { slug: "jawa-barat", kode: "32", name: "Jawa Barat", level: "provinsi", total: 62140, score: { ...computeIkd(95.4, 97.1, 93.8), trend: 1.6 } },
  { slug: "jawa-tengah", kode: "33", name: "Jawa Tengah", level: "provinsi", total: 50210, score: { ...computeIkd(94.9, 96.7, 93.1), trend: 1.3 } },
  { slug: "di-yogyakarta", kode: "34", name: "DI Yogyakarta", level: "provinsi", total: 2870, score: { ...computeIkd(96.8, 98.2, 95.1), trend: 0.9 } },
  { slug: "jawa-timur", kode: "35", name: "Jawa Timur", level: "provinsi", total: 41820, score: { ...computeIkd(94.2, 96.9, 92.0), trend: 1.2 } },
  { slug: "bali", kode: "51", name: "Bali", level: "provinsi", total: 3210, score: { ...computeIkd(95.6, 97.4, 94.2), trend: 1.0 } },
  { slug: "sulawesi-selatan", kode: "24", name: "Sulawesi Selatan", level: "provinsi", total: 12780, score: { ...computeIkd(91.8, 95.0, 89.6), trend: 1.7 } },
  { slug: "nusa-tenggara-timur", kode: "53", name: "Nusa Tenggara Timur", level: "provinsi", total: 11204, score: { ...computeIkd(88.1, 92.6, 84.9), trend: 2.3 } },
  { slug: "dki-jakarta", kode: "31", name: "DKI Jakarta", level: "provinsi", total: 4260, score: { ...computeIkd(97.1, 98.4, 95.8), trend: 0.6 } },
];

export const ikdDistricts: IkdRegion[] = [
  { slug: "kabupaten-bandung", kode: "3204", name: "Kabupaten Bandung", level: "kabupaten", total: 1684, parents: { slug: "jawa-barat", name: "Jawa Barat" }, score: { ...computeIkd(95.1, 96.8, 93.0), trend: 1.4 } },
  { slug: "kota-bandung", kode: "3273", name: "Kota Bandung", level: "kabupaten", total: 1284, parents: { slug: "jawa-barat", name: "Jawa Barat" }, score: { ...computeIkd(97.2, 98.1, 95.6), trend: 0.8 } },
  { slug: "kabupaten-bogor", kode: "3201", name: "Kabupaten Bogor", level: "kabupaten", total: 2740, parents: { slug: "jawa-barat", name: "Jawa Barat" }, score: { ...computeIkd(94.0, 96.2, 92.3), trend: 1.5 } },
  { slug: "kota-surabaya", kode: "3578", name: "Kota Surabaya", level: "kabupaten", total: 1148, parents: { slug: "jawa-timur", name: "Jawa Timur" }, score: { ...computeIkd(96.4, 97.8, 94.6), trend: 0.9 } },
  { slug: "kabupaten-malang", kode: "3514", name: "Kabupaten Malang", level: "kabupaten", total: 1476, parents: { slug: "jawa-timur", name: "Jawa Timur" }, score: { ...computeIkd(94.6, 96.4, 92.8), trend: 1.2 } },
  { slug: "kabupaten-bantul", kode: "3403", name: "Kabupaten Bantul", level: "kabupaten", total: 719, parents: { slug: "di-yogyakarta", name: "DI Yogyakarta" }, score: { ...computeIkd(96.2, 97.9, 94.1), trend: 0.7 } },
  { slug: "kabupaten-sleman", kode: "3404", name: "Kabupaten Sleman", level: "kabupaten", total: 623, parents: { slug: "di-yogyakarta", name: "DI Yogyakarta" }, score: { ...computeIkd(96.8, 98.0, 94.8), trend: 1.0 } },
  { slug: "kota-banda-aceh", kode: "1106", name: "Kota Banda Aceh", level: "kabupaten", total: 246, parents: { slug: "nanggroe-aceh-darussalam", name: "Nanggroe Aceh Darussalam" }, score: { ...computeIkd(93.4, 95.6, 91.2), trend: 1.3 } },
  { slug: "kota-denpasar", kode: "5107", name: "Kota Denpasar", level: "kabupaten", total: 412, parents: { slug: "bali", name: "Bali" }, score: { ...computeIkd(96.1, 97.6, 94.9), trend: 0.5 } },
  { slug: "kabupaten-kupang", kode: "5301", name: "Kabupaten Kupang", level: "kabupaten", total: 512, parents: { slug: "nusa-tenggara-timur", name: "Nusa Tenggara Timur" }, score: { ...computeIkd(89.0, 93.2, 86.4), trend: 2.0 } },
];

export function findIkdProvince(slug: string | undefined) {
  return slug ? ikdProvinces.find((p) => p.slug === slug) : undefined;
}

export function findIkdDistrict(parentSlug: string | undefined, slug: string | undefined) {
  if (!parentSlug || !slug) return undefined;
  return ikdDistricts.find((d) => d.slug === slug && d.parents?.slug === parentSlug);
}
