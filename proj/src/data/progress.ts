import type { DistrictProgress, ProvinceProgress, SubdistrictProgress } from "../types";

/**
 * Helper keeps sent/total/progress internally consistent (values are demo
 * data; progress is derived, never hand-typed).
 */
const row = (kode: string, name: string, total: number, sent: number): SubdistrictProgress & { kode: string } => ({
  kode,
  name,
  total,
  sent,
  progress: Math.round((sent / total) * 1000) / 10,
});

const dist = (kode: string, name: string, subdistricts: SubdistrictProgress[]): DistrictProgress => {
  const total = subdistricts.reduce((sum, s) => sum + s.total, 0);
  const sent = subdistricts.reduce((sum, s) => sum + s.sent, 0);
  return { kode, name, total, sent, progress: Math.round((sent / total) * 1000) / 10, subdistricts };
};

const prov = (kode: string, name: string, districts: DistrictProgress[]): ProvinceProgress => {
  const total = districts.reduce((sum, d) => sum + d.total, 0);
  const sent = districts.reduce((sum, d) => sum + d.sent, 0);
  return { kode, name, total, sent, progress: Math.round((sent / total) * 1000) / 10, districts };
};

/**
 * Drill-down sample for /progres: 8 provinsi with districts and subdistricts.
 * Codes follow BPS/Dapodik administrative conventions; figures are illustrative.
 */
export const provinceProgress: ProvinceProgress[] = [
  prov("11", "Aceh", [
    dist("1101", "Kabupaten Aceh Selatan", [
      row("1101010", "Bakongan", 18, 16),
      row("1101020", "Kluet Utara", 22, 20),
      row("1101050", "Pasie Raja", 24, 21),
    ]),
    dist("1106", "Kota Banda Aceh", [
      row("1106010", "Baiturrahman", 34, 33),
      row("1106030", "Kuta Alam", 41, 40),
      row("1106070", "Syiah Kuala", 28, 26),
    ]),
    dist("1112", "Kabupaten Bener Meriah", [
      row("1112010", "Bukit", 26, 21),
      row("1112050", "Timang Gajah", 30, 25),
      row("1112070", "Permata", 24, 21),
    ]),
  ]),
  prov("31", "DKI Jakarta", [
    dist("3101", "Kabupaten Kepulauan Seribu", [
      row("3101010", "Pulau Panggang", 8, 8),
      row("3101030", "Pulau Tidung", 12, 11),
      row("3101060", "Pulau Kelapa", 14, 13),
    ]),
    dist("3102", "Jakarta Pusat", [
      row("3102010", "Gambir", 52, 52),
      row("3102050", "Menteng", 47, 46),
      row("3102070", "Cempaka Putih", 39, 38),
    ]),
    dist("3174", "Jakarta Selatan", [
      row("3174020", "Cilandak", 33, 33),
      row("3174060", "Kebayoran Baru", 35, 34),
      row("3174110", "Pancoran", 29, 28),
    ]),
  ]),
  prov("32", "Jawa Barat", [
    dist("3201", "Kabupaten Bogor", [
      row("3201040", "Nanggung", 31, 29),
      row("3201100", "Ciampea", 58, 54),
      row("3201290", "Cibinong", 66, 65),
    ]),
    dist("3271", "Kota Bekasi", [
      row("3271020", "Bekasi Timur", 71, 70),
      row("3271070", "Rawalumbu", 68, 65),
      row("3271090", "Pondok Gede", 63, 60),
    ]),
    dist("3273", "Kota Bandung", [
      row("3273040", "Coblong", 48, 48),
      row("3273090", "Cibeunying Kaler", 52, 51),
      row("3273100", "Sumur Bandung", 37, 36),
    ]),
  ]),
  prov("34", "DI Yogyakarta", [
    dist("3402", "Kabupaten Kulon Progo", [
      row("3402020", "Temon", 33, 32),
      row("3402070", "Wates", 47, 46),
      row("3402110", "Pengasih", 39, 37),
    ]),
    dist("3403", "Kabupaten Bantul", [
      row("3403010", "Kretek", 41, 39),
      row("3403040", "Kasihan", 55, 54),
      row("3403130", "Pleret", 49, 49),
    ]),
    dist("3404", "Kabupaten Sleman", [
      row("3404020", "Gamping", 44, 43),
      row("3404060", "Ngaglik", 58, 57),
      row("3404220", "Tempel", 36, 34),
    ]),
  ]),
  prov("35", "Jawa Timur", [
    dist("3514", "Kabupaten Malang", [
      row("3514010", "Singosari", 61, 60),
      row("3514030", "Kepanjen", 54, 53),
      row("3514100", "Dampit", 49, 46),
    ]),
    dist("3523", "Kabupaten Sidoarjo", [
      row("3523030", "Waru", 72, 71),
      row("3523130", "Krian", 46, 43),
      row("3523160", "Tulangan", 31, 29),
    ]),
    dist("3578", "Kota Surabaya", [
      row("3578040", "Genteng", 38, 38),
      row("3578110", "Tegalsari", 31, 30),
      row("3578230", "Mulyorejo", 42, 41),
    ]),
  ]),
  prov("51", "Bali", [
    dist("5101", "Kabupaten Jembrana", [
      row("5101010", "Negara", 42, 40),
      row("5101030", "Mendoyo", 37, 35),
      row("5101050", "Pekutatan", 24, 22),
    ]),
    dist("5104", "Kabupaten Bangli", [
      row("5104010", "Bangli", 44, 42),
      row("5104020", "Kintamani", 63, 59),
      row("5104040", "Tembuku", 29, 27),
    ]),
    dist("5107", "Kota Denpasar", [
      row("5107010", "Denpasar Barat", 58, 57),
      row("5107020", "Denpasar Timur", 71, 70),
      row("5107040", "Denpasar Selatan", 66, 65),
    ]),
  ]),
  prov("53", "Nusa Tenggara Timur", [
    dist("5301", "Kabupaten Kupang", [
      row("5301020", "Kupang Tengah", 38, 31),
      row("5301100", "Amarasi", 44, 36),
      row("5301180", "Fatuleu", 33, 26),
    ]),
    dist("5303", "Kabupaten Timor Tengah Utara", [
      row("5303010", "Miomaffo Barat", 35, 29),
      row("5303050", "Kefamenanu", 58, 52),
      row("5303110", "Insana", 27, 21),
    ]),
    dist("5371", "Kota Kupang", [
      row("5371020", "Alak", 28, 26),
      row("5371050", "Kupang Timur", 39, 37),
      row("5371070", "Oebobo", 45, 44),
    ]),
  ]),
  prov("24", "Sulawesi Selatan", [
    dist("2401", "Kabupaten Kepulauan Selayar", [
      row("2401010", "Bontosikuyu", 44, 37),
      row("2401020", "Buki", 26, 23),
      row("2401070", "Pasilambena", 21, 17),
    ]),
    dist("2406", "Kabupaten Bulukumba", [
      row("2406010", "Ujung Bulu", 57, 53),
      row("2406060", "Bontobahari", 33, 29),
      row("2406130", "Rilau Ale", 39, 35),
    ]),
    dist("2411", "Kota Makassar", [
      row("2411040", "Biringkanaya", 68, 66),
      row("2411050", "Manggala", 45, 44),
      row("2411070", "Rappocini", 62, 61),
    ]),
  ]),
];

export function findProvince(kode: string | undefined): ProvinceProgress | undefined {
  return kode ? provinceProgress.find((p) => p.kode === kode) : undefined;
}

export function findDistrict(province: ProvinceProgress | undefined, kode: string | undefined) {
  if (!province || !kode) return undefined;
  return province.districts.find((d) => d.kode === kode);
}
