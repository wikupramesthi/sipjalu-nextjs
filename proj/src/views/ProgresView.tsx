"use client";

import { MapPin } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { Callout } from "../components/ui/Callout";
import { RegionTable } from "../components/data/RegionTable";
import { ProgressTable } from "../components/data/ProgressTable";
import { SummaryCards } from "../components/data/SummaryCards";
import { findDistrict, findProvince, provinceProgress } from "../data/progress";
import { buildPath } from "../config/routes";
import { SUMMARY_STATS } from "../data/site";
import { TOTALS } from "../data/levels";
import { compact, nf } from "../lib/format";
import { NotFoundView } from "./NotFoundView";

interface ProgresViewProps {
  provinsi?: string;
  kabupaten?: string;
  kecamatan?: string;
}

/** /progres + drill-down provinsi → kabupaten → kecamatan (halaman bertingkat). */
export function ProgresView({ provinsi, kabupaten, kecamatan }: ProgresViewProps) {
  // ---- Kecamatan (leaf) ------------------------------------------------------
  if (kecamatan) {
    const province = findProvince(provinsi);
    const district = province ? findDistrict(province, kabupaten) : undefined;
    const sub = district?.subdistricts.find((s) => s.kode === kecamatan);
    if (!sub || !district || !province) {
      return <NotFoundView reason="Kecamatan tersebut tidak ada pada katalog wilayah contoh." />;
    }
    return (
      <>
        <PageHero
          eyebrow="PROGRES PENGIRIMAN DATA"
          title={`Progres Kecamatan ${sub.name}`}
          description={`${district.name}, ${province.name}`}
          crumbs={[
            { label: "Progres", href: buildPath("/progres") },
            { label: province.name, href: buildPath("/progres", province.kode) },
            { label: district.name, href: buildPath("/progres", province.kode, district.kode) },
            { label: sub.name },
          ]}
          stats={[
            { label: "Sekolah", value: nf(sub.total), note: "data contoh" },
            { label: "Terkirim", value: nf(sub.sent), note: "data contoh" },
            { label: "Progres", value: `${nf(sub.progress)}%`, note: "semester berjalan" },
          ]}
        />
        <section className="sj-section-block">
          <div className="sj-container">
            <div className="sj-list-panel sj-gauge-grid">
              <div className="sj-gauge-copy">
                <h3>
                  <MapPin size={16} /> {sub.name}
                </h3>
                <p>
                  Data terkirim <strong>{nf(sub.sent)}</strong> dari <strong>{nf(sub.total)}</strong> satuan
                  pendidikan ({nf(sub.progress)}%). Kecamatan ini adalah level terendah pada catalog
                  demonstrasi.
                </p>
              </div>
            </div>
            <Callout tone="warn">Seluruh angka pada halaman ini adalah contoh.</Callout>
          </div>
        </section>
      </>
    );
  }

  // ---- Kabupaten -------------------------------------------------------------
  if (kabupaten) {
    const province = findProvince(provinsi);
    const district = province ? findDistrict(province, kabupaten) : undefined;
    if (!district || !province) {
      return <NotFoundView reason="Kabupaten/kota tersebut tidak ada pada katalog wilayah contoh." />;
    }
    return (
      <>
        <PageHero
          eyebrow="PROGRES PENGIRIMAN DATA"
          title={`Progres ${district.name}`}
          description={`Terdiri dari ${district.subdistricts.length} kecamatan di ${province.name}.`}
          crumbs={[
            { label: "Progres", href: buildPath("/progres") },
            { label: province.name, href: buildPath("/progres", province.kode) },
            { label: district.name },
          ]}
          stats={[
            { label: "Sekolah", value: nf(district.total), note: "data contoh" },
            { label: "Terkirim", value: nf(district.sent), note: "data contoh" },
            { label: "Progres", value: `${nf(district.progress)}%`, note: "semester berjalan" },
          ]}
        />
        <section className="sj-section-block">
          <div className="sj-container">
            <h2 className="sj-block-title">Perkembangan per Kecamatan</h2>
            <RegionTable
              base="/progres"
              parentSegments={[province.kode, district.kode]}
              rows={district.subdistricts}
              nextLabel="Lihat detail kecamatan"
            />
          </div>
        </section>
      </>
    );
  }

  // ---- Provinsi --------------------------------------------------------------
  if (provinsi) {
    const province = findProvince(provinsi);
    if (!province) {
      return <NotFoundView reason="Provinsi tersebut tidak ada pada katalog wilayah contoh." />;
    }
    return (
      <>
        <PageHero
          eyebrow="PROGRES PENGIRIMAN DATA"
          title={`Progres Pengiriman ${province.name}`}
          description="Pilih kabupaten/kota untuk melihat rincian per kecamatan."
          crumbs={[{ label: "Progres", href: buildPath("/progres") }, { label: province.name }]}
          stats={[
            { label: "Sekolah", value: nf(province.total), note: "data contoh" },
            { label: "Terkirim", value: nf(province.sent), note: "data contoh" },
            { label: "Progres", value: `${nf(province.progress)}%`, note: "semester berjalan" },
          ]}
        />
        <section className="sj-section-block">
          <div className="sj-container">
            <h2 className="sj-block-title">Perkembangan per Kabupaten / Kota</h2>
            <RegionTable
              base="/progres"
              parentSegments={[province.kode]}
              rows={province.districts}
              nextLabel="Lihat detail kabupaten"
            />
          </div>
        </section>
      </>
    );
  }

  // ---- Nasional --------------------------------------------------------------
  return (
    <>
      <PageHero
        eyebrow="PEMANTAUAN NASIONAL"
        title="Progres Pengiriman Data Pendidikan"
        description="Ringkasan pengiriman data satuan pendidikan nasional, per jenjang, dan per wilayah. Klik wilayah untuk menggulir ke level berikutnya."
        crumbs={[{ label: "Progres" }]}
        stats={[
          { label: "Total Sekolah", value: nf(TOTALS.schools), note: "katalog contoh" },
          { label: "Data Terkirim", value: nf(TOTALS.sent), note: "katalog contoh" },
          { label: "Peserta Didik", value: compact(TOTALS.students), note: "katalog contoh" },
          { label: "Guru & Tendik", value: compact(TOTALS.teachers), note: "katalog contoh" },
        ]}
      />

      <section className="sj-section-block sj-tint">
        <div className="sj-container">
          <div className="sj-section-top">
            <div>
              <span className="sj-eyebrow">PER JENJANG</span>
              <h2>Progres Nasional per Jenjang Pendidikan</h2>
              <p>Filter jenjang &amp; semester, lalu ekspor (data contoh).</p>
            </div>
          </div>
          <SummaryCards items={SUMMARY_STATS} semesterNote="vs semester lalu" />
          <ProgressTable variant="full" />
        </div>
      </section>

      <section className="sj-section-block">
        <div className="sj-container">
          <div className="sj-section-top">
            <div>
              <span className="sj-eyebrow">PER WILAYAH</span>
              <h2>Progres per Provinsi</h2>
              <p>Contoh agregasi 8 provinsi dengan wilayah turunan (kabupaten & kecamatan).</p>
            </div>
          </div>
          <RegionTable
            base="/progres"
            parentSegments={[]}
            rows={provinceProgress}
            nextLabel="Lihat detail provinsi"
          />
          <Callout tone="info">
            Versi demonstrasi menampilkan sebagian kecil wilayah saja — pada portal sebenarnya daftar
            provinsi lengkap dan dapat digulir hingga kecamatan melalui API wilayah.
          </Callout>
        </div>
      </section>
    </>
  );
}

