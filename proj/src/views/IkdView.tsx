"use client";

import { ArrowRight, Gauge as GaugeIcon, ShieldCheck } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { Callout } from "../components/ui/Callout";
import { Gauge } from "../components/ui/Gauge";
import { ProgressBar } from "../components/ui/ProgressBar";
import { SmartLink } from "../components/ui/SmartLink";
import { nf } from "../lib/format";
import { buildPath } from "../config/routes";
import { findIkdDistrict, findIkdProvince, ikdDistricts, ikdProvinces, nationalIkd } from "../data/ikd";
import { NotFoundView } from "./NotFoundView";

const nfPct = new Intl.NumberFormat("id-ID", { minimumFractionDigits: 1 });

interface IkdViewProps {
  provinsi?: string;
  kabupaten?: string;
}

/** /ikd + drill-down provinsi/kabupaten — Indeks Kualitas Data (komposit + komponen). */
export function IkdView({ provinsi, kabupaten }: IkdViewProps) {
  // ---- Kabupaten -------------------------------------------------------------
  if (kabupaten) {
    const province = findIkdProvince(provinsi);
    const district = province ? findIkdDistrict(province.slug, kabupaten) : undefined;
    if (!district || !province) {
      return <NotFoundView reason="Kabupaten tersebut tidak tersedia pada katalog IKD contoh." />;
    }
    return (
      <>
        <PageHero
          eyebrow="INDEKS KUALITAS DATA"
          title={`IKD ${district.name}`}
          description={`${nf(district.total)} satuan pendidikan · ${province.name}`}
          crumbs={[
            { label: "IKD", href: buildPath("/ikd") },
            { label: province.name, href: buildPath("/ikd", province.slug) },
            { label: district.name },
          ]}
          aside={
            <div className="sj-hero-gauge">
              <Gauge value={district.score.composite} caption="Skor IKD" />
              <span>Skor IKD (0–100) — data contoh</span>
            </div>
          }
        />
        <IkdSection region={district} />
      </>
    );
  }

  // ---- Provinsi --------------------------------------------------------------
  if (provinsi) {
    const province = findIkdProvince(provinsi);
    if (!province) {
      return <NotFoundView reason="Provinsi tersebut tidak tersedia pada katalog IKD contoh." />;
    }
    const children = ikdDistricts.filter((d) => d.parents?.slug === province.slug);
    return (
      <>
        <PageHero
          eyebrow="INDEKS KUALITAS DATA"
          title={`IKD ${province.name}`}
          description={`${nf(province.total)} satuan pendidikan · ${children.length} kabupaten contoh terdaftar`}
          crumbs={[{ label: "IKD", href: buildPath("/ikd") }, { label: province.name }]}
          aside={
            <div className="sj-hero-gauge">
              <Gauge value={province.score.composite} caption="Skor IKD" />
              <span>Skor IKD (0–100) — data contoh</span>
            </div>
          }
        />
        <IkdSection region={province}>
          <div className="sj-list-panel">
            <table className="sj-data-table">
              <thead>
                <tr>
                  <th scope="col">Kabupaten / Kota</th>
                  <th scope="col">Skor IKD</th>
                  <th scope="col" className="sj-col-progress">Komponen terlemah</th>
                  <th scope="col" className="sj-col-mini">Rincian</th>
                </tr>
              </thead>
              <tbody>
                {children.map((child) => {
                  const weakest = [...child.score.components].sort((a, b) => a.score - b.score)[0];
                  return (
                    <tr key={child.slug}>
                      <td><span className="sj-td-name"><GaugeIcon size={15} />{child.name}</span></td>
                      <td className="sj-num"><strong>{nfPct.format(child.score.composite)}</strong></td>
                      <td>
                        <span className="sj-mini-combo">
                          <span>{weakest.label}</span>
                          <ProgressBar value={weakest.score} tone={weakest.score >= 92 ? "green" : "amber"} />
                          <span className="sj-num">{nfPct.format(weakest.score)}</span>
                        </span>
                      </td>
                      <td>
                        <SmartLink className="sj-drill-link" href={buildPath("/ikd", province.slug, child.slug)}>
                          detail <ArrowRight size={12} />
                        </SmartLink>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        </IkdSection>
      </>
    );
  }

  // ---- Nasional --------------------------------------------------------------
  return (
    <>
      <PageHero
        eyebrow="PUSAT MUTU DATA"
        title="Indeks Kualitas Data (IKD)"
        description="Ukuran agregat kelengkapan, validitas, dan ketepatan waktu data pendidikan. Pilih provinsi untuk menggulir ke kabupaten."
        crumbs={[{ label: "IKD" }]}
        aside={
          <div className="sj-hero-gauge">
            <Gauge value={nationalIkd.score.composite} caption="Skor IKD nasional" />
            <span>{nationalIkd.score.trend > 0 ? `↑ ${nationalIkd.score.trend} dari triwulan lalu` : `↓ ${Math.abs(nationalIkd.score.trend)} dari triwulan lalu`} · data contoh</span>
          </div>
        }
      />
      <IkdSection region={nationalIkd}>
        <div className="sj-list-panel">
          <table className="sj-data-table">
            <thead>
              <tr>
                <th scope="col">Provinsi</th>
                <th scope="col">Sekolah</th>
                <th scope="col">Skor IKD</th>
                <th scope="col">Predikat</th>
                <th scope="col" className="sj-col-mini">Rincian</th>
              </tr>
            </thead>
            <tbody>
              {ikdProvinces.map((provinceRegion) => (
                <tr key={provinceRegion.slug}>
                  <td><span className="sj-td-name"><ShieldCheck size={15} />{provinceRegion.name}</span></td>
                  <td className="sj-num">{nf(provinceRegion.total)}</td>
                  <td className="sj-num"><strong>{nfPct.format(provinceRegion.score.composite)}</strong></td>
                  <td>
                    <span className="sj-status">
                      <span />
                      {provinceRegion.score.composite >= 95 ? "Sangat Baik" : provinceRegion.score.composite >= 90 ? "Baik" : "Perlu Perbaikan"}
                    </span>
                  </td>
                  <td>
                    <SmartLink className="sj-drill-link" href={buildPath("/ikd", provinceRegion.slug)}>
                      detail <ArrowRight size={12} />
                    </SmartLink>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </IkdSection>
    </>
  );
}

/** Panel penjelasan + komponen skor, dipakai di semua level IKD. */
function IkdSection({ region, children }: { region: typeof nationalIkd; children?: React.ReactNode }) {
  return (
    <section className="sj-section-block">
      <div className="sj-container">
        <div className="sj-list-panel sj-gauge-grid">
          <div>
            <h3 className="sj-panel-title">Bagaimana IKD dihitung?</h3>
            <p>
              Skor komposit adalah rata-rata berbobot tiga komponen mutu data (bobot ilustratif):
              kelengkapan 40%, validitas 35%, ketepatan waktu 25%. Penjelasan ini umum untuk
              demonstrasi dan bukan hasil pengukuran resmi.
            </p>
          </div>
          <div className="sj-component-stack"><div className="sj-component-row">
            <span>Kelengkapan</span>
            <ProgressBar value={region.score.components[0].score} />
            <span className="sj-num">{nfPct.format(region.score.components[0].score)}</span>
          </div>
          <div className="sj-component-row">
            <span>Validitas</span>
            <ProgressBar value={region.score.components[1].score} tone="green" />
            <span className="sj-num">{nfPct.format(region.score.components[1].score)}</span>
          </div>
          <div className="sj-component-row">
            <span>Ketepatan Waktu</span>
            <ProgressBar value={region.score.components[2].score} tone="blue" />
            <span className="sj-num">{nfPct.format(region.score.components[2].score)}</span>
          </div></div>
        </div>
        {children}
        <Callout tone="warn">Seluruh skor dan wilayah pada halaman ini adalah data contoh.</Callout>
      </div>
    </section>
  );
}
