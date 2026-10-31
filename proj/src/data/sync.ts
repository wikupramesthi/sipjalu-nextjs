/**
 * Data "Grafik Jumlah Sinkronisasi 30 Hari Terakhir" — deterministik (seeded),
 * dibuat agar cocok dengan KPI tetap portal contoh:
 * Total 513.067 · rata-rata 17.102 · puncak 30.703 · tren +12,5%.
 */

export interface SyncPoint {
  date: string; // ISO
  label: string; // "27 Sep"
  value: number;
}

export interface SyncJenjangShare {
  name: string;
  share: number;
  colors: [string, string, string, string];
}

// PRNG mulberry32 agar hasil sama di server prerender dan klien (hydration-safe).
function mulberry32(seed: number) {
  let a = seed;
  return () => {
    a |= 0;
    a = (a + 0x6d2b79f5) | 0;
    let t = Math.imul(a ^ (a >>> 15), 1 | a);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

export const SYNC_KPI = {
  total30d: 513067,
  avgDaily: 17102,
  peak: 30703,
  trend7d: "+12.5%",
} as const;

export function buildSyncSeries(endISO = "2026-10-29"): SyncPoint[] {
  const rand = mulberry32(20261029);
  const days = 30;
  const end = new Date(`${endISO}T00:00:00`);
  const raw: number[] = [];
  for (let i = 0; i < days; i += 1) {
    const weekly = i % 7 === 5 || i % 7 === 6 ? 0.55 : 1; // akhir pekan lebih sepi
    const ramp = i > days - 7 ? 1.15 : 1; // tren naik di 7 hari terakhir
    raw.push(Math.round((9000 + rand() * 18000) * weekly * ramp));
  }
  // skala agar total == KPI dan puncak == KPI.peak
  const sum = raw.reduce((a, b) => a + b, 0);
  const scaleTotal = SYNC_KPI.total30d / sum;
  const peakRaw = Math.max(...raw) * scaleTotal;
  const scalePeak = SYNC_KPI.peak / peakRaw;
  const fmt = new Intl.DateTimeFormat("id-ID", { day: "2-digit", month: "short" });
  return raw.map((v, i) => {
    const d = new Date(end);
    d.setDate(end.getDate() - (days - 1 - i));
    return {
      date: d.toISOString().slice(0, 10),
      label: fmt.format(d),
      value: Math.round(v * scaleTotal * scalePeak),
    };
  });
}

/** Pembagian "Detail Per Jenjang" — share tetap per jenjang (contoh). */
export const SYNC_SHARES: SyncJenjangShare["name"][] = ["PAUD", "SD", "SMP", "SMA/SMK", "SLB/PKBM"];
export const SYNC_SPLIT = [0.16, 0.46, 0.21, 0.11, 0.06];
export const SYNC_COLORS: string[] = ["#25804a", "#60856b", "#16834a", "#57a873", "#97b89f"];

export function stackPoint(point: SyncPoint): Record<string, number> {
  const out: Record<string, number> = {};
  SYNC_SHARES.forEach((name, i) => {
    out[name] = Math.round(point.value * SYNC_SPLIT[i]);
  });
  return out;
}
