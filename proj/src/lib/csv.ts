/**
 * CSV building + download, hardened against spreadsheet formula injection.
 *
 * Cells that start with =, +, -, @, tab or newline are prefixed with a single
 * quote so Excel/Sheets treat them as text even if a crafted dataset ever
 * reaches an export. A UTF-8 BOM keeps Excel happy with Indonesian glyphs.
 */

import { sanitizeInput } from "./security";

const FORMULA_STARTERS = /^[=+\-@\t\r]/;

export function csvCell(value: string | number): string {
  const text = typeof value === "number" ? String(value) : sanitizeInput(String(value), 500);
  const guarded = FORMULA_STARTERS.test(text) ? `'${text}` : text;
  return `"${guarded.replace(/"/g, '""')}"`;
}

export function buildCsv(header: string[], rows: (string | number)[][], preamble = ""): string {
  const lines = [
    ...rows.map((row) => row.map(csvCell).join(",")),
  ];
  if (header.length) lines.unshift(header.map(csvCell).join(","));
  return `\uFEFF${preamble ? `${preamble}\n` : ""}${lines.join("\n")}`;
}

/** Triggers a client-side download; revokes the blob URL immediately after. */
export function downloadCsv(filename: string, csv: string): void {
  const safeName = filename.replace(/[^\w.\-]/g, "_");
  const url = URL.createObjectURL(new Blob([csv], { type: "text/csv;charset=utf-8;" }));
  const anchor = document.createElement("a");
  anchor.href = url;
  anchor.download = safeName;
  anchor.rel = "noopener";
  document.body.appendChild(anchor);
  anchor.click();
  anchor.remove();
  URL.revokeObjectURL(url);
}
