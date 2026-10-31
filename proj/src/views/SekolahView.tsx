"use client";

import { ArrowRight, BadgeCheck, Building2, ExternalLink, GraduationCap, MapPin, School, Users } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { Callout } from "../components/ui/Callout";
import { SmartLink } from "../components/ui/SmartLink";
import { findSchoolByNpsn } from "../data/schools";
import { buildPath } from "../config/routes";
import { parseNpsn, sanitizeInput } from "../lib/security";
import { useQueryParams } from "../hooks/use-query-params";
import { OFFICIAL } from "../data/site";
import { NotFoundView } from "./NotFoundView";

const nf = new Intl.NumberFormat("id-ID");

/** /sekolah?npsn=…… — profil satuan pendidikan dari katalog contoh. */
export function SekolahView() {
  const params = useQueryParams();
  const raw = params.get("npsn");
  const npsn = raw === null ? undefined : parseNpsn(raw);
  const school = findSchoolByNpsn(npsn);

  if (raw === null || raw === "") {
    return (
      <>
        <PageHero eyebrow="PROFIL SATUAN PENDIDIKAN" title="Sekolah mana yang ingin Anda lihat?" crumbs={[{ label: "Profil Sekolah" }]} />
        <section className="sj-section-block">
          <div className="sj-container">
            <Callout tone="info">
              Buka profil sekolah melalui <SmartLink href={buildPath("/pencarian")}>Pencarian</SmartLink>,
              atau langsung lewat URL seperti <code className="sj-inline-code">/sekolah/?npsn=20219258</code>.
            </Callout>
            <div className="sj-school-results">
              <SmartLink className="sj-school-result" href={`${buildPath("/sekolah")}?npsn=20219258`}>
                <span className="sj-result-icon"><School size={17} /></span>
                <span><strong>SMP Negeri 1 Bandung</strong><small>NPSN 20219258 · Kota Bandung</small></span>
                <ArrowRight size={18} />
              </SmartLink>
              <SmartLink className="sj-school-result" href={`${buildPath("/sekolah")}?npsn=20403174`}>
                <span className="sj-result-icon"><School size={17} /></span>
                <span><strong>SMA Negeri 1 Yogyakarta</strong><small>NPSN 20403174 · Kota Yogyakarta</small></span>
                <ArrowRight size={18} />
              </SmartLink>
            </div>
          </div>
        </section>
      </>
    );
  }

  if (!npsn) {
    return (
      <>
        <PageHero eyebrow="PROFIL SATUAN PENDIDIKAN" title="Format NPSN tidak valid" crumbs={[{ label: "Profil Sekolah", href: buildPath("/sekolah") }, { label: "NPSN tidak valid" }]} />
        <section className="sj-section-block">
          <div className="sj-container">
            <Callout tone="alert">
              NPSN harus tepat 8 digit angka. Nilai yang diberikan (“{sanitizeInput(raw, 24)}”) ditolak
              sebelum ditampilkan — ini bagian dari validasi input aplikasi.
            </Callout>
            <SmartLink className="sj-primary-button" href={buildPath("/pencarian")}>
              <ArrowRight size={15} /> Gunakan Pencarian sekolah
            </SmartLink>
          </div>
        </section>
      </>
    );
  }

  if (!school) {
    return <NotFoundView reason={`Sekolah dengan NPSN ${npsn} tidak ada pada katalog contoh demonstrasi.`} />;
  }

  return (
    <>
      <PageHero
        eyebrow={`PROFIL ${school.level} · ${school.status.toUpperCase()}`}
        title={school.name}
        description={`NPSN ${school.npsn} · ${school.city}, ${school.province}`}
        crumbs={[{ label: "Pencarian", href: buildPath("/pencarian") }, { label: school.name }]}
        stats={[
          { label: "Peserta Didik", value: nf.format(school.students), note: "data contoh" },
          { label: "Guru & Tendik", value: nf.format(school.teachers), note: "data contoh" },
          { label: "Ruang Kelas", value: nf.format(school.rts), note: "data contoh" },
        ]}
      />
      <section className="sj-section-block">
        <div className="sj-container">
          <div className="sj-grid-cards">
            <div className="sj-kv-panel">
              <h3><Building2 size={15} /> IDENTITAS</h3>
              <dl>
                <dt>NPSN</dt><dd>{school.npsn}</dd>
                <dt>Jenjang</dt><dd>{school.level}</dd>
                <dt>Status</dt><dd>{school.status}</dd>
                <dt>Didirikan</dt><dd>{school.established}</dd>
                <dt>Akreditasi</dt><dd>{school.accreditation}</dd>
              </dl>
            </div>
            <div className="sj-kv-panel">
              <h3><MapPin size={15} /> LOKASI</h3>
              <dl>
                <dt>Provinsi</dt><dd>{school.province}</dd>
                <dt>Kabupaten / Kota</dt><dd>{school.city}</dd>
                <dt>Kecamatan</dt><dd>{school.district}</dd>
                <dt>Alamat</dt><dd>{school.address}</dd>
              </dl>
            </div>
            <div className="sj-kv-panel">
              <h3><BadgeCheck size={15} /> SUMBER DAYA</h3>
              <dl>
                <dt>Peserta Didik</dt><dd><span><GraduationCap size={14}/></span>{nf.format(school.students)}</dd>
                <dt>Guru & Tendik</dt><dd><span><Users size={14}/></span>{nf.format(school.teachers)}</dd>
                <dt>Ruang Kelas</dt><dd>{nf.format(school.rts)}</dd>
                <dt>Rasio</dt><dd>1:{Math.round(school.students / school.teachers)} siswa/guru</dd>
              </dl>
            </div>
          </div>
          <Callout tone="warn">
            Profil ini data contoh untuk pengujian antarmuka — tidak diverifikasi dan tidak tersambung ke
            data sekolah resmi.
          </Callout>
          <div className="sj-row-actions">
            <SmartLink className="sj-primary-button" href={buildPath("/pencarian")}>
              Cari sekolah lain <ArrowRight size={15} />
            </SmartLink>
            <SmartLink className="sj-outline-button" href={OFFICIAL.portal}>
              Cek sekolah di portal resmi <ExternalLink size={14} />
            </SmartLink>
          </div>
        </div>
      </section>
    </>
  );
}
