"use client";

import { useEffect, useState } from "react";
import { ArrowRight } from "lucide-react";
import { Hero } from "../components/home/Hero";
import { Ticker } from "../components/home/Ticker";
import { NoticeDialog } from "../components/home/NoticeDialog";
import { NewsPreview } from "../components/home/NewsPreview";
import { PembaruanTiles } from "../components/home/PembaruanTiles";
import { SyncChart } from "../components/home/SyncChart";
import { ProgressTable } from "../components/data/ProgressTable";
import { SummaryCards } from "../components/data/SummaryCards";
import { SmartLink } from "../components/ui/SmartLink";
import { SUMMARY_STATS } from "../data/site";
import { buildPath } from "../config/routes";

/**
 * Beranda — urutan.section mengikuti portal referensi:
 * Hero → Info Terkini → Dapodik dalam Angka + tabel jenjang →
 * Grafik sinkronisasi 30 hari → Pembaruan aplikasi → Berita terkini.
 */
export function HomeView() {
  const [notice, setNotice] = useState(false);

  // Auto-tampilkan pengumuman sekali per sesi, seperti portal (dapat ditutup).
  useEffect(() => {
    let seen = false;
    try {
      seen = sessionStorage.getItem("dapodik-notice") === "1";
    } catch {
      seen = false;
    }
    if (!seen) {
      const timer = window.setTimeout(() => setNotice(true), 900);
      return () => window.clearTimeout(timer);
    }
  }, []);

  function closeNotice() {
    setNotice(false);
    try {
      sessionStorage.setItem("dapodik-notice", "1");
    } catch {
      /* storage diblokir (private mode): abaikan */
    }
  }

  return (
    <>
      <Hero />
      <Ticker onOpen={() => setNotice(true)} />

      <section className="sj-data-section" id="progres">
        <svg
          className="sj-data-grid-pattern"
          aria-hidden="true"
          focusable="false"
        >
          <defs>
            <pattern id="sj-data-grid" width="32" height="32" patternUnits="userSpaceOnUse" x="-1" y="-1">
              <path d="M.5 32V.5H32" fill="none" stroke="currentColor" strokeDasharray="4 2" />
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#sj-data-grid)" />
        </svg>
        <div className="sj-container">
          <div className="sj-section-head">
            <span className="sj-eyebrow">Progres Pengiriman</span>
            <h2>Dapodik dalam Angka</h2>
            <p>Ringkasan Data Pokok Pendidikan saat ini.</p>
          </div>
          <SummaryCards items={SUMMARY_STATS} semesterNote="vs semester lalu" />
          <ProgressTable />
        </div>
      </section>

      <SyncChart />
      <PembaruanTiles />

      <section className="sj-news-section sj-container" id="berita">
        <div className="sj-section-top">
          <div>
            <span className="sj-eyebrow">Berita &amp; Informasi</span>
            <h2>
              Kabar Dapodik Terkini<span className="sj-title-dot">.</span>
            </h2>
            <p>Update kebijakan, program, dan informasi terkait Dapodik.</p>
          </div>
          <SmartLink className="sj-text-button" href={buildPath("/berita")}>
            Lihat Semua Berita <ArrowRight size={16} />
          </SmartLink>
        </div>
        <NewsPreview />
      </section>

      <NoticeDialog open={notice} onClose={closeNotice} />
    </>
  );
}
