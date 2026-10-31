"use client";

import { ArrowDownToLine, ExternalLink, HardDrive, MonitorSmartphone, Package, ShieldCheck } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { Callout } from "../components/ui/Callout";
import { SmartLink } from "../components/ui/SmartLink";
import { downloadItems, systemRequirements } from "../data/downloads";
import { OFFICIAL, SITE } from "../data/site";

const KIND_ICONS = {
  installer: HardDrive,
  patch: Package,
  driver: MonitorSmartphone,
  manual: ShieldCheck,
} as const;

/** /unduh-aplikasi — katalog installer/patch + langkah instalasi (demo). */
export function UnduhView() {
  const installer = downloadItems.find((d) => d.kind === "installer") ?? downloadItems[0];

  return (
    <>
      <PageHero
        eyebrow="APLIKASI DAPODIK"
        title="Unduh Aplikasi Dapodik"
        description="Aplikasi resmi hanya dibagikan melalui portal Kemendikdasmen. Halaman demonstrasi ini menampilkan katalog dan prasyarat — tombol selalu mengarah ke portal resmi."
        crumbs={[{ label: "Unduh Aplikasi" }]}
        stats={[
          { label: "Versi Rilis", value: SITE.appVersion, note: "contoh" },
          { label: "Diperbarui", value: "20 Sep 2026", note: "contoh" },
        ]}
      />

      <section className="sj-section-block">
        <div className="sj-container">
          <div className="sj-dl-card">
            <div className="sj-dl-card-main">
              <span className="sj-eyebrow">INSTALLER UTAMA</span>
              <h2>{installer.label}</h2>
              <p className="sj-dl-lead">{installer.description}</p>
              <div className="sj-spec-row">
                <div>
                  <span>Versi</span>
                  <strong>{installer.version}</strong>
                </div>
                <div>
                  <span>Ukuran</span>
                  <strong>{installer.size}</strong>
                </div>
                <div>
                  <span>Diperbarui</span>
                  <strong>{installer.updated}</strong>
                </div>
                <div>
                  <span>Prasyarat</span>
                  <strong>{systemRequirements[0].value}</strong>
                </div>
              </div>
            </div>
            <div className="sj-dl-card-side">
              <span className="sj-big">Dapodik</span>
              <span className="sj-big">{SITE.appVersion}</span>
              <small>Akurat · Mutakhir · Berkelanjutan</small>
              <SmartLink className="sj-primary-button sj-block-cta" href={OFFICIAL.portal}>
                <ArrowDownToLine size={16} /> Buka Portal Resmi
              </SmartLink>
            </div>
          </div>

          <div className="sj-two-col">
            <div>
              <h2 className="sj-block-title">Berikutnya setelah installer</h2>
              <div className="sj-steps">
                <div className="sj-step">
                  <div>
                    <strong>Unduh installer dari portal resmi</strong>
                    <p>Pastikan versi mengikuti semester pendataan berjalan.</p>
                  </div>
                </div>
                <div className="sj-step">
                  <div>
                    <strong>Pasang patch sesuai urutan</strong>
                    <p>Jalankan patch dari nomor terlama ke terbaru sebelum login pertama.</p>
                  </div>
                </div>
                <div className="sj-step">
                  <div>
                    <strong>Login & pilih semester aktif</strong>
                    <p>Gunakan akun yang dibuat Kepala Sekolah dan divalidasi Dinas.</p>
                  </div>
                </div>
                <div className="sj-step">
                  <div>
                    <strong>Mutakhirkan data lalu sinkronisasi</strong>
                    <p>Selesaikan validasi merah sebelum mengirim data.</p>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <h2 className="sj-block-title">Prasyarat sistem</h2>
              <div className="sj-kv-panel">
                <dl>
                  {systemRequirements.map((req) => (
                    <div key={req.label} className="sj-contents">
                      <dt>{req.label}</dt>
                      <dd>{req.value}</dd>
                    </div>
                  ))}
                </dl>
              </div>
              <Callout tone="danger">
                Jangan pernah mengunduh aplikasi atau memasukkan kredensial dari tautan di luar domain
                resmi Kemendikdasmen. Demonstrasi ini tidak menyimpan berkas installer apa pun.
              </Callout>
            </div>
          </div>

          <h2 className="sj-block-title">Seluruh berkas pada katalog rilis</h2>
          <div className="sj-list-panel">
            {downloadItems.map((item) => {
              const Icon = KIND_ICONS[item.kind] ?? Package;
              return (
                <div className="sj-list-row" key={item.id}>
                  <span className="sj-list-icon" aria-hidden="true">
                    <Icon size={19} />
                  </span>
                  <div className="sj-list-main">
                    <strong>{item.label}</strong>
                    <p>{item.description}</p>
                  </div>
                  <div className="sj-list-meta">
                    <span className="sj-meta-pill sj-v">{item.version}</span>
                    <span className="sj-meta-note">{item.size} · {item.updated}</span>
                    <SmartLink className="sj-outline-button sj-tiny" href={OFFICIAL.portal}>
                      Portal resmi <ExternalLink size={13} />
                    </SmartLink>
                  </div>
                </div>
              );
            })}
          </div>

          <Callout tone="warn">
            Versi, ukuran, dan tanggal pada halaman ini adalah contoh tampilan. Kondisi rilis sebenarnya
            hanya berlaku pada portal resmi.
          </Callout>
        </div>
      </section>
    </>
  );
}
