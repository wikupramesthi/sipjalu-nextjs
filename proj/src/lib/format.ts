/** Shared formatters — single place for Indonesian locale rules. */

const numberFmt = new Intl.NumberFormat("id-ID");
const compactFmt = new Intl.NumberFormat("id-ID", { notation: "compact", maximumFractionDigits: 1 });

export function nf(value: number): string {
  return numberFmt.format(value);
}

export function compact(value: number): string {
  return compactFmt.format(value);
}

/** "20 September 2026" from an ISO date string (no external deps). */
export function formatDate(iso: string): string {
  const date = new Date(`${iso}T00:00:00`);
  if (Number.isNaN(date.getTime())) return iso;
  return new Intl.DateTimeFormat("id-ID", { day: "numeric", month: "long", year: "numeric" }).format(date);
}

export function slugify(text: string): string {
  return text
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "")
    .slice(0, 80);
}
