"use client";

import { Activity, CalendarDays, BarChart3, CircleGauge, TrendingUp, TriangleAlert } from "lucide-react";
import { useMemo, useRef, useState } from "react";
import { SITE } from "../../data/site";
import { SYNC_KPI, buildSyncSeries, stackPoint, SYNC_SHARES, SYNC_COLORS, type SyncPoint } from "../../data/sync";
import { useCountUp } from "../../hooks/use-count-up";
import { useReveal } from "../../hooks/use-reveal";

const nfTh = new Intl.NumberFormat("id-ID");
const nfKb = (value: number) => `${Math.round(value / 1000)}rb`;

/**
 * "Grafik Jumlah Sinkronisasi 30 Hari Terakhir" — dibangun dari data contoh
 * deterministik (lib internal; tanpa fetch eksternal). Interaksi: hover bar
 * memunculkan tooltip; tab Ringkasan/Detail; animasi grow saat masuk viewport.
 */
export function SyncChart() {
  const [view, setView] = useState<"total" | "jenjang">("total");
  const [hover, setHover] = useState<{ index: number; left: number; top: number } | null>(null);
  const chartWrapRef = useRef<HTMLDivElement | null>(null);
  const reveal = useReveal<HTMLDivElement>(0);
  const series = useMemo<SyncPoint[]>(() => buildSyncSeries(SITE.lastSync), []);

  const max = Math.max(...series.map((p) => p.value));
  const gridMax = Math.ceil((max * 1.18) / 1000) * 1000;
  const width = 900;
  const height = 260;
  const padB = 26;
  const bw = (width / series.length) * 0.58;
  const step = width / series.length;
  const y = (v: number) => height - padB - (v / gridMax) * (height - padB - 12);

  function showTooltip(index: number, clientX: number, clientY: number) {
    const bounds = chartWrapRef.current?.getBoundingClientRect();
    if (!bounds) return;
    const tooltipWidth = 240;
    const tooltipHeight = view === "jenjang" ? 128 : 76;
    const maxLeft = Math.max(8, bounds.width - tooltipWidth - 8);
    const maxTop = Math.max(8, bounds.height - tooltipHeight - 8);

    setHover({
      index,
      left: Math.min(Math.max(clientX - bounds.left + 14, 8), maxLeft),
      top: Math.min(Math.max(clientY - bounds.top + 14, 8), maxTop),
    });
  }

  function focusPoint(index: number) {
    const svgBounds = chartWrapRef.current?.querySelector("svg")?.getBoundingClientRect();
    if (!svgBounds) return;
    showTooltip(index, svgBounds.left + ((index + 0.5) / series.length) * svgBounds.width, svgBounds.top + svgBounds.height * 0.35);
  }

  return (
    <section className="sj-section-block sj-tint" id="sinkronisasi">
      <div className="sj-container">
        <div className="sj-section-top sj-stack sj-sync-section-head">
          <span className="sj-eyebrow sj-with-icon"><Activity size={13} /> Aktivitas Sinkronisasi</span>
          <h2>Grafik Jumlah Sinkronisasi 30 Hari Terakhir</h2>
          <p>Pantau aktivitas sinkronisasi data Dapodik — contoh data untuk demonstrasi tampilan.</p>
        </div>

        <div className="sj-chart-card" ref={reveal.ref}>
          <div className="sj-kpi-row" role="list">
            {[
              { icon: CalendarDays, label: "Total 30 Hari", value: SYNC_KPI.total30d },
              { icon: CircleGauge, label: "Rata-rata Harian", value: SYNC_KPI.avgDaily },
              { icon: TriangleAlert, label: "Puncak Tertinggi", value: SYNC_KPI.peak },
              { icon: TrendingUp, label: "Tren 7 Hari", value: 0, raw: SYNC_KPI.trend7d },
            ].map((kpi) => (
              <div className="sj-kpi" role="listitem" key={kpi.label}>
                <span className="sj-kpi-label"><kpi.icon size={13} /> {kpi.label}</span>
                <strong>{kpi.raw ? kpi.raw : <KpiValue target={kpi.value} />}</strong>
              </div>
            ))}
          </div>
          <div className="sj-chart-head">
            <div>
              <h3><BarChart3 size={15} /> Aktivitas Harian</h3>
              <p>Arahkan kursor atau fokuskan grafik untuk melihat rinciannya.</p>
            </div>
            <div className="sj-seg" role="group" aria-label="Mode tampilan grafik">
              <button aria-pressed={view === "total"} onClick={() => setView("total")}>Ringkasan</button>
              <button aria-pressed={view === "jenjang"} onClick={() => setView("jenjang")}>Detail Per Jenjang</button>
            </div>
          </div>
          <div
            ref={chartWrapRef}
            className={`sj-chart-wrap ${reveal.className}`}
            onPointerLeave={() => setHover(null)}
          >
            <svg viewBox={`0 0 ${width} ${height}`} className="sj-sync-svg" role="img" aria-label="Grafik jumlah sinkronisasi harian 30 hari terakhir">
              <defs>
                <linearGradient id="sj-sync-bar-gradient" x1="0" x2="0" y1="0" y2="1">
                  <stop offset="0%" stopColor="#28794c" />
                  <stop offset="100%" stopColor="#82b991" />
                </linearGradient>
              </defs>
              {[0, 0.25, 0.5, 0.75, 1].map((f) => {
                const gy = y(gridMax * f);
                return (
                  <g key={f}>
                    <line x1="0" x2={width} y1={gy} y2={gy} className="sj-grid-line" vectorEffect={f === 0 ? undefined : "non-scaling-stroke"} />
                    <text x="4" y={gy - 5} className="sj-axis-label">{f === 0 ? "0" : nfKb(gridMax * f)}</text>
                  </g>
                );
              })}
              <line
                x1="42"
                x2={width - 4}
                y1={y(SYNC_KPI.avgDaily)}
                y2={y(SYNC_KPI.avgDaily)}
                className="sj-average-line"
              />
              <text
                x={width - 5}
                y={y(SYNC_KPI.avgDaily) - 6}
                className="sj-average-label"
                textAnchor="end"
              >
                Rata-rata
              </text>
              {series.map((point, i) => {
                const x = i * step + (step - bw) / 2;
                if (view === "total") {
                  const h = height - padB - y(point.value);
                  return (
                    <rect
                      key={point.date}
                      x={x}
                      y={y(point.value)}
                      width={bw}
                      height={Math.max(1.5, h)}
                      rx={2.5}
                      fill="url(#sj-sync-bar-gradient)"
                      className={`sj-bar sj-bar-${hover?.index === i ? "hover" : ""}`}
                      style={{ ["--d" as string]: `${i * 28}ms` }}
                      opacity={hover === null || hover.index === i ? 1 : 0.55}
                    />
                  );
                }
                const parts = stackPoint(point);
                let acc = 0;
                return SYNC_SHARES.map((name, k) => {
                  const v = parts[name];
                  const segTop = y(acc + v);
                  const segH = Math.max(0, y(acc) - segTop);
                  acc += v;
                  return (
                    <rect
                      key={`${point.date}-${name}`}
                      x={x}
                      y={segTop}
                      width={bw}
                      height={segH}
                      rx={1.5}
                      fill={SYNC_COLORS[k % SYNC_COLORS.length]}
                      style={{ ["--d" as string]: `${i * 28 + k * 40}ms` }}
                      className={`sj-bar sj-stacked sj-bar-${hover?.index === i ? "hover" : ""}`}
                      opacity={hover === null || hover.index === i ? 1 : 0.6}
                    />
                  );
                });
              })}
              {series.map((point, i) => {
                return (
                  <rect
                    key={`hit-${point.date}`}
                    x={i * step}
                    y={0}
                    width={step}
                    height={height}
                    fill="transparent"
                    onPointerEnter={(event) => showTooltip(i, event.clientX, event.clientY)}
                    onPointerMove={(event) => showTooltip(i, event.clientX, event.clientY)}
                    onFocus={() => focusPoint(i)}
                    tabIndex={0}
                    role="button"
                    aria-label={`${point.label}: ${nfTh.format(point.value)} sinkronisasi`}
                  />
                );
              })}
              {series.map((point, i) => {
                if (i % 4 !== 0 && i !== series.length - 1) return null;
                const cx = i * step + step / 2;
                return <text key={`lbl-${point.date}`} x={cx} y={height - 6} className="sj-axis-label sj-center">{point.label}</text>;
              })}
            </svg>
            {hover ? (
              <div
                className="sj-chart-tooltip"
                role="tooltip"
                style={{ left: hover.left, top: hover.top }}
              >
                <strong className="sj-chart-tooltip-date">{series[hover.index].label}</strong>
                <span className="sj-chart-tooltip-total">
                  {nfTh.format(series[hover.index].value)} <small>sinkronisasi</small>
                </span>
                {view === "jenjang" ? (
                  <span className="sj-chart-tooltip-breakdown">
                    {SYNC_SHARES.map((name, index) => (
                      <span key={name}>
                        <i style={{ backgroundColor: SYNC_COLORS[index] }} />
                        {name}: {nfTh.format(stackPoint(series[hover.index])[name])}
                      </span>
                    ))}
                  </span>
                ) : null}
              </div>
            ) : null}
          </div>
          <div className="sj-chart-foot">
            <span><span className="sj-live-dot" /> Rata-rata {nfTh.format(SYNC_KPI.avgDaily)} / hari</span>
            <span>{SITE.chartSource}</span>
          </div>
        </div>
      </div>
    </section>
  );
}

function KpiValue({ target }: { target: number }) {
  const { ref, display } = useCountUp(target, 1200);
  return <strong ref={ref}>{new Intl.NumberFormat("id-ID").format(display)}</strong>;
}
