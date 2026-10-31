/** Horizontal meter used in drill-down tables (pure CSS, CSP-safe). */
export function ProgressBar({ value, tone = "blue" }: { value: number; tone?: "blue" | "green" | "amber" | "red" }) {
  const safe = Math.max(0, Math.min(100, Number.isFinite(value) ? value : 0));
  const tint = tone === "blue" ? "" : ` sj-tint-${tone}`;
  return (
    <div className={`sj-progress-track${tint}`} role="img" aria-label={`Progres ${safe.toFixed(1).replace(".", ",")}%`}>
      <span style={{ ["--progress-width" as string]: `${safe}%` }} />
    </div>
  );
}

/** Compact cell: bar + tabular number, for wide data tables. */
export function BarCell({ value }: { value: number }) {
  const tone = value >= 95 ? "green" : value >= 90 ? "blue" : value >= 85 ? "amber" : "red";
  return (
    <div className="sj-bar-cell">
      <ProgressBar value={value} tone={tone} />
      <strong>{value.toFixed(1).replace(".", ",")}%</strong>
    </div>
  );
}