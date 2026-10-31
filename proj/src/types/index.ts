/** Domain types for the Dapodik-inspired demo portal. */

export type Jenjang = "PAUD" | "SD" | "SMP" | "SMA" | "SMK" | "SLB";

/** Baris tabel progres per jenjang (kolom mengikuti portal resmi). */
export interface JenjangStat {
  name: string;
  long: string;
  description: string;
  total: number;
  sent: number;
  students: number;
  teachers: number;
  staff: number;
  progress: number;
}

/** @deprecated alias lama; pakai JenjangStat */
export type LevelStat = JenjangStat;

export interface School {
  npsn: string;
  name: string;
  level: Jenjang;
  status: "Negeri" | "Swasta";
  city: string;
  province: string;
  district: string;
  address: string;
  established: string;
  accreditation: "A" | "B" | "C";
  students: number;
  teachers: number;
  rts: number;
}

export type NewsCategory = "Berita" | "Pengumuman" | "Informasi";

export interface Article {
  slug: string;
  title: string;
  excerpt: string;
  body: string[];
  date: string;
  isoDate: string;
  category: NewsCategory;
  author: string;
  featured?: boolean;
}

export interface FaqEntry {
  id: string;
  category: string;
  question: string;
  answer: string;
}

export interface DownloadItem {
  id: string;
  label: string;
  kind: "installer" | "patch" | "driver" | "manual";
  version: string;
  size: string;
  updated: string;
  description: string;
  href: string;
  requiredFor?: string;
}

export interface PanduanDoc {
  id: string;
  category: "Panduan Aplikasi" | "Juknis" | "Regulasi" | "Materi";
  title: string;
  type: "PDF" | "Doc" | "TIF";
  sizeMb: number;
  version: string;
  description: string;
  target: string;
}

export interface SemesterOption {
  id: string;
  label: string;
}

export interface ProvinceProgress {
  kode: string;
  name: string;
  total: number;
  sent: number;
  progress: number;
  districts: DistrictProgress[];
}

export interface DistrictProgress {
  kode: string;
  name: string;
  total: number;
  sent: number;
  progress: number;
  subdistricts: SubdistrictProgress[];
}

export interface SubdistrictProgress {
  kode: string;
  name: string;
  total: number;
  sent: number;
  progress: number;
}

export interface IkdComponent {
  label: string;
  description: string;
  score: number;
  weight: string;
}

export interface IkdScore {
  composite: number;
  trend: number;
  components: IkdComponent[];
}

export interface IkdRegion {
  slug: string;
  kode: string;
  name: string;
  level: "provinsi" | "kabupaten";
  total: number;
  score: IkdScore;
  parents?: { slug: string; name: string };
}
