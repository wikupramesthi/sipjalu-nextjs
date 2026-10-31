"use client";

import { Compass, Home, Search } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { SmartLink } from "../components/ui/SmartLink";
import { buildPath } from "../config/routes";

/** 404 state page — also reused inline when a slug/NPSN is unknown. */
export function NotFoundView({ reason }: { reason?: string }) {
  return (
    <>
      <PageHero
        eyebrow="HALAMAN TIDAK DITEMUKAN"
        title="404 — Halaman tidak ditemukan"
        crumbs={[{ label: "Tidak Ditemukan" }]}
      />
      <div className="sj-state-page sj-container">
        <div className="sj-state-symbol">
          <Compass size={40} />
        </div>
        <p className="sj-state-code">STATUS 404</p>
        <h1>Yang kami cari tidak ada di sini</h1>
        <p className="sj-state-desc">
          {reason ?? "Tautan yang Anda buka tidak cocok dengan halaman mana pun pada demonstrasi portal ini."}
        </p>
        <div className="sj-state-actions">
          <SmartLink className="sj-primary-button" href="/">
            <Home size={16} /> Kembali ke Beranda
          </SmartLink>
          <SmartLink className="sj-outline-button" href={buildPath("/pencarian")}>
            <Search size={15} /> Cari sekolah
          </SmartLink>
        </div>
        <div className="sj-list-panel">
          <SmartLink className="sj-list-row" href={buildPath("/progres")}>
            <span className="sj-list-icon"><Compass size={19} /></span>
            <span className="sj-list-main">
              <strong>Lihat angka nasional</strong>
              <p>Progres pengiriman data per jenjang dan wilayah.</p>
            </span>
            <span className="sj-meta-pill">PROGRES</span>
          </SmartLink>
          <SmartLink className="sj-list-row" href={buildPath("/faq")}>
            <span className="sj-list-icon"><Home size={19} /></span>
            <span className="sj-list-main">
              <strong>Buka pusat bantuan</strong>
              <p>Instalasi, akun, peserta didik, GTK, sinkronisasi, validasi.</p>
            </span>
            <span className="sj-meta-pill">FAQ</span>
          </SmartLink>
          <SmartLink className="sj-list-row" href={buildPath("/unduh-aplikasi")}>
            <span className="sj-list-icon"><Search size={19} /></span>
            <span className="sj-list-main">
              <strong>Halaman unduh & panduan aplikasi</strong>
              <p>Installer dan patch hanya melalui portal resmi.</p>
            </span>
            <span className="sj-meta-pill">UNDUH</span>
          </SmartLink>
        </div>
      </div>
    </>
  );
}
