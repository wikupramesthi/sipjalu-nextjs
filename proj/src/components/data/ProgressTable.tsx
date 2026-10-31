"use client";

import { useMemo, useState } from "react";
import { ArrowRight, BarChart3, BookOpen, ChevronDown, Download, Send } from "lucide-react";
import { adjustForSemester, levels as allLevels, statusFor, TOTAL, TOTALS } from "../../data/levels";
import { OFFICIAL, SEMESTERS } from "../../data/site";
import { SmartLink } from "../ui/SmartLink";
import { buildPath } from "../../config/routes";
import { buildCsv, downloadCsv } from "../../lib/csv";

const nf = new Intl.NumberFormat("id-ID", { maximumFractionDigits: 1 });
const JENJANGS = ["Semua Jenjang", ...allLevels.map((l) => l.name)];

interface Props {
  /** home: tanpa toolbar; full: dengan filter+semester+ekspor (dipakai di /progres). */
  variant?: "home" | "full";
}

/** Tabel "Progres Pengiriman Data per Jenjang" mengikuti struktur kolom portal. */
export function ProgressTable({ variant = "home" }: Props) {
  const [semester, setSemester] = useState<string>(SEMESTERS[0]);
  const [filter, setFilter] = useState<string>("Semua Jenjang");
  const [exported, setExported] = useState(false);

  const shown = useMemo(() => {
    const base = filter === "Semua Jenjang" ? allLevels : allLevels.filter((l) => l.name === filter);
    return semester === SEMESTERS[0] ? base : base.map(adjustForSemester);
  }, [semester, filter]);

  const sumSent = shown.reduce((a, l) => a + l.sent, 0);

  function exportCsv() {
    downloadCsv(
      `progres-dapodik-${semester.toLowerCase().replace(/[^a-z0-9]+/g, "-")}.csv`,
      buildCsv(
        ["Jenjang", "Total Sekolah", "Data Terkirim", "Peserta Didik", "Guru", "Tendik", "Progres (%)"],
        shown.map((l) => [l.name, l.total, l.sent, l.students, l.teachers, l.staff, l.progress]),
        "Data contoh demonstrasi Portal Dapodik (bukan data resmi)",
      ),
    );
    setExported(true);
    window.setTimeout(() => setExported(false), 2600);
  }

  const toolbar = variant === "full" ? (
    <div className="sj-table-actions">
      <div className="sj-select-wrapper sj-compact">
        <select aria-label="Filter jenjang pendidikan" value={filter} onChange={(e) => setFilter(e.target.value)}>
          {JENJANGS.map((j) => (<option key={j}>{j}</option>))}
        </select>
        <ChevronDown size={14} />
      </div>
      <div className="sj-select-wrapper sj-compact">
        <select aria-label="Pilih semester" value={semester} onChange={(e) => setSemester(e.target.value)}>
          {SEMESTERS.map((s) => (<option key={s}>{s}</option>))}
        </select>
        <ChevronDown size={14} />
      </div>
      <button className="sj-outline-button sj-export-button" onClick={exportCsv}>
        <Download size={15} />
        <span>{exported ? "Terunduh" : "Ekspor CSV"}</span>
      </button>
    </div>
  ) : null;

  return (
    <div className={`sj-progress-panel${variant === "home" ? " sj-progress-home" : ""}`} id="jenjang">
      <div className="sj-panel-head">
        <div>
          <h3>{variant === "home" ? <Send size={14} /> : <BarChart3 size={18} />} Progres Pengiriman Data per Jenjang</h3>
          <p>Status pengiriman data sekolah berdasarkan jenjang pendidikan</p>
        </div>
        <div className="sj-panel-head-right">
          {toolbar}
          <span className="sj-sent-badge">
            {variant === "home" ? (
              <>
                <span>Total Terkirim</span>
                <strong>{nf.format(TOTAL.sent)}</strong>
              </>
            ) : (
              <>
                <Send size={12} /> Total Terkirim <strong>{nf.format(sumSent)}</strong>
              </>
            )}
          </span>
        </div>
      </div>

      <div className="sj-table-scroll">
        <table>
          <caption className="sj-sr-only">Progres pengiriman data contoh per jenjang pendidikan</caption>
          {variant === "home" ? (
            <colgroup>
              <col style={{ width: "10.3%" }} />
              <col style={{ width: "12.4%" }} />
              <col style={{ width: "12.4%" }} />
              <col style={{ width: "14.9%" }} />
              <col style={{ width: "9.1%" }} />
              <col style={{ width: "9.1%" }} />
              <col style={{ width: "17.9%" }} />
              <col style={{ width: "13.9%" }} />
            </colgroup>
          ) : null}
          <thead>
            <tr>
              <th scope="col">Jenjang</th>
              <th scope="col">Total Sekolah</th>
              <th scope="col">Data Terkirim</th>
              <th scope="col">Peserta Didik</th>
              <th scope="col">Guru</th>
              <th scope="col">Tendik</th>
              <th scope="col">Progress</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            {shown.map((l) => {
              const status = statusFor(l.progress);
              return (
                <tr key={l.name}>
                  <td>
                    <span className={`sj-level-icon sj-${l.name.toLowerCase()}`}><BookOpen size={15} /></span>
                    <strong>{l.name}</strong>
                  </td>
                  <td>{nf.format(l.total)}</td>
                  <td>{nf.format(l.sent)}</td>
                  <td>{nf.format(l.students)}</td>
                  <td>{nf.format(l.teachers)}</td>
                  <td>{nf.format(l.staff)}</td>
                  <td>
                    <div className="sj-progress-cell">
                      <div className={`sj-progress-track sj-tint-${status.tone}`}><span style={{ width: `${l.progress}%` }} /></div>
                      <strong>{nf.format(l.progress)}%</strong>
                    </div>
                  </td>
                  <td><span className={`sj-status-chip sj-${status.tone}`}><i />{status.label}</span></td>
                </tr>
              );
            })}
            {variant === "home" ? (
              <tr className="sj-total-row">
                <td><strong>Total</strong></td>
                <td>{nf.format(TOTALS.schools)}</td>
                <td>{nf.format(TOTALS.sent)}</td>
                <td>{nf.format(TOTALS.students)}</td>
                <td>{nf.format(TOTAL.teachers)}</td>
                <td>{nf.format(TOTAL.staff)}</td>
                <td>
                  <div className="sj-progress-cell">
                    <div className="sj-progress-track sj-tint-green"><span style={{ width: `${TOTALS.sent / TOTALS.schools * 100}%` }} /></div>
                    <strong>{nf.format(Math.round((TOTALS.sent / TOTALS.schools) * 1000) / 10)}%</strong>
                  </div>
                </td>
                <td><span className="sj-status-chip sj-green"><i />Sangat Baik</span></td>
              </tr>
            ) : null}
          </tbody>
        </table>
      </div>

      <div className="sj-panel-foot">
        <span>
          {variant === "home" ? (
            <>
              Data contoh ·{" "}
              <SmartLink className="sj-src" href={OFFICIAL.portal}>Sumber: Rekap Dapodik Kemendikdasmen RI (2026/2027 Semester Ganjil)</SmartLink>
            </>
          ) : (
            <>
              <span className="sj-live-dot" />
              {shown.length} jenjang · data contoh
            </>
          )}
        </span>
        {variant === "home" ? (
          <SmartLink className="sj-more-link" href={buildPath("/progres")}> Selengkapnya <ArrowRight size={12} /></SmartLink>
        ) : (
          <span>Semester {semester}</span>
        )}
      </div>
    </div>
  );
}
