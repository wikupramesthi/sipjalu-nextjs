import type { JenjangStat } from "../types";

/**
 * Statistik per jenjang — struktur kolom mengikuti tabel resmi portal
 * (Guru & Tendik dipisah, plus jenjang non-formal SNT/SR/PKBM/SKB).
 * Angka illustratif untuk demonstrasi.
 */
export const levels: JenjangStat[] = [
  { name: "SNT", long: "Santri", description: "Pendidikan Kesetaraan Pesantren", total: 17, sent: 17, students: 1306, teachers: 223, staff: 36, progress: 100 },
  { name: "SR", long: "Sekolah Rakyat", description: "Pendidikan Layanan Khusus", total: 156, sent: 0, students: 0, teachers: 0, staff: 0, progress: 0 },
  { name: "PAUD", long: "PAUD", description: "Pendidikan Anak Usia Dini", total: 208906, sent: 200173, students: 6416792, teachers: 504101, staff: 243600, progress: 95.8 },
  { name: "SD", long: "SD", description: "Sekolah Dasar", total: 149935, sent: 149319, students: 23972315, teachers: 1473371, staff: 364923, progress: 99.6 },
  { name: "SMP", long: "SMP", description: "Sekolah Menengah Pertama", total: 44638, sent: 44068, students: 10140350, teachers: 671957, staff: 182416, progress: 98.7 },
  { name: "SMA", long: "SMA", description: "Sekolah Menengah Atas", total: 15407, sent: 15174, students: 5461905, teachers: 346431, staff: 95336, progress: 98.5 },
  { name: "SMK", long: "SMK", description: "Sekolah Menengah Kejuruan", total: 14387, sent: 14135, students: 5128963, teachers: 328250, staff: 91096, progress: 98.2 },
  { name: "SLB", long: "SLB", description: "Sekolah Luar Biasa", total: 2484, sent: 2448, students: 170361, teachers: 29023, staff: 9832, progress: 98.6 },
  { name: "PKBM", long: "PKBM", description: "Pusat Kegiatan Belajar Masyarakat", total: 11371, sent: 10088, students: 1686391, teachers: 48314, staff: 16309, progress: 88.7 },
  { name: "SKB", long: "SKB", description: "Sanggar Kegiatan Belajar", total: 511, sent: 500, students: 148988, teachers: 4957, staff: 2429, progress: 97.8 },
];

export function statusFor(progress: number): { label: string; tone: "green" | "blue" | "amber" | "red" } {
  if (progress >= 95) return { label: "Sangat Baik", tone: "green" };
  if (progress >= 85) return { label: "Baik", tone: "blue" };
  if (progress > 0) return { label: "Perlu Perhatian", tone: "amber" };
  return { label: "Perlu Perhatian", tone: "red" };
}

export const levelsTotals = levels.reduce(
  (acc, l) => ({
    total: acc.total + l.total,
    sent: acc.sent + l.sent,
    students: acc.students + l.students,
    teachers: acc.teachers + l.teachers,
    staff: acc.staff + l.staff,
  }),
  { total: 0, sent: 0, students: 0, teachers: 0, staff: 0 },
);

export const TOTAL = {
  ...levelsTotals,
  progress: Math.round((levelsTotals.sent / levelsTotals.total) * 1000) / 10,
};

/** Back-compat shape used by drill-down views (teachers = guru + tendik). */
export const TOTALS = {
  schools: levelsTotals.total,
  sent: levelsTotals.sent,
  students: levelsTotals.students,
  teachers: levelsTotals.teachers + levelsTotals.staff,
};

/** Derive one-semester-earlier figures for the demo toggle (never negative). */
export const adjustForSemester = (level: JenjangStat): JenjangStat => {
  const progress = Math.max(0, Math.round((level.progress - 1.8) * 10) / 10);
  return {
    ...level,
    sent: Math.round((level.total * progress) / 100),
    progress,
  };
};
