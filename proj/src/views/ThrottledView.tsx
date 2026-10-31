"use client";

import { Home, RefreshCw, TriangleAlert } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { SmartLink } from "../components/ui/SmartLink";

/**
 * Halaman /429 — status "terlalu banyak permintaan". Pada portal aslinya
 * halaman ini muncul ketika API menolak permintaan berlebihan. Di demo, ia
 * menjadi state page informatif (bukan error React) dan siap dipakai bila
 * backend nyata menambahkan rate limiting.
 */
export function ThrottledView() {
  return (
    <>
      <PageHero
        eyebrow="LAYANAN SEMENTARA SIBUK"
        title="429 — Terlalu Banyak Permintaan"
        crumbs={[{ label: "Layanan Sibuk" }]}
      />
      <div className="sj-state-page sj-container">
        <div className="sj-state-symbol sj-warn">
          <TriangleAlert size={38} />
        </div>
        <p className="sj-state-code">STATUS 429</p>
        <h1>Permintaan Anda dibatasi sementara</h1>
        <p className="sj-state-desc">
          Sistem mendeteksi permintaan sangat tinggi dari perangkat atau jaringan Anda. Tunggu
          beberapa saat, lalu coba kembali. Demonstrasi ini tidak memanggil API apa pun sehingga
          Anda dapat melanjutkan melalui tautan navigasi di bawah dengan aman.
        </p>
        <div className="sj-state-actions">
          <button className="sj-primary-button" type="button" onClick={() => window.location.reload()}>
            <RefreshCw size={15} /> Coba lagi sekarang
          </button>
          <SmartLink className="sj-outline-button" href="/">
            <Home size={15} /> Kembali ke Beranda
          </SmartLink>
        </div>
      </div>
    </>
  );
}
