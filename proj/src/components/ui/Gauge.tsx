/** Circular score gauge rendered with conic-gradient — no JS/chart deps. */
export function Gauge({ value, caption }: { value: number; caption?: string }) {
  const safe = Math.max(0, Math.min(100, Number.isFinite(value) ? value : 0));
  const color = safe >= 95 ? "#2f9d6f" : safe >= 90 ? "#57a873" : safe >= 85 ? "#c98a3f" : "#b05332";
  return (
    <div
      className="sj-gauge"
      style={{ ["--gauge" as string]: safe, ["--gauge-color" as string]: color }}
      role="img"
      aria-label={`${caption ?? "Skor"} ${safe.toFixed(1).replace(".", ",")} persen`}
    >
      <strong>{safe.toFixed(1).replace(".", ",")}</strong>
    </div>
  );
}
