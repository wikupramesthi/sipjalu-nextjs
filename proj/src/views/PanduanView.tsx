"use client";

import { useState } from "react";
import { FileArchive, FileText, HardDrive, ScrollText, ExternalLink } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { Chip } from "../components/ui/Chip";
import { Callout } from "../components/ui/Callout";
import { SmartLink } from "../components/ui/SmartLink";
import { panduanDocs } from "../data/panduan";
import { OFFICIAL, SITE } from "../data/site";
import type { PanduanDoc } from "../types";

const CATEGORIES = ["Semua", "Panduan Aplikasi", "Juknis", "Regulasi", "Materi"] as const;
type Filter = (typeof CATEGORIES)[number];

const TYPE_ICONS: Record<PanduanDoc["type"], { icon: typeof FileText; cls: string }> = {
  PDF: { icon: FileArchive, cls: "pdf" },
  Doc: { icon: ScrollText, cls: "doc" },
  TIF: { icon: HardDrive, cls: "tif" },
};

/** /panduan — katalog panduan & dokumentasi (tautan membuka portal resmi). */
export function PanduanView() {
  const [filter, setFilter] = useState<Filter>("Semua");
  const shown = filter === "Semua" ? panduanDocs : panduanDocs.filter((d) => d.category === filter);

  return (
    <>
      <PageHero
        eyebrow="DOKUMENTASI RESMI"
        title="Panduan & Dokumentasi Aplikasi"
        description="Buku manual, petunjuk teknis, dan materi pendukung penggunaan aplikasi Dapodik. Demonstrasi ini tidak menyimpan berkas — setiap tautan membuka portal resmi."
        crumbs={[{ label: "Panduan" }]}
        stats={[
          { label: "Dokumen", value: String(panduanDocs.length), note: "katalog contoh" },
          { label: "Format", value: "PDF · TIF · Doc", note: "bervariasi" },
        ]}
      />
      <section className="sj-section-block">
        <div className="sj-container">
          <div className="sj-chip-row" role="group" aria-label="Filter kategori dokumen">
            {CATEGORIES.map((category) => (
              <Chip
                key={category}
                label={category}
                count={category === "Semua" ? panduanDocs.length : panduanDocs.filter((d) => d.category === category).length}
                active={filter === category}
                onClick={() => setFilter(category)}
              />
            ))}
          </div>

          <div className="sj-list-panel sj-mt-list">
            {shown.map((doc) => {
              const meta = TYPE_ICONS[doc.type] ?? TYPE_ICONS.PDF;
              const Icon = meta.icon;
              return (
                <div className="sj-list-row" key={doc.id}>
                  <span className={`sj-list-icon sj-${meta.cls}`} aria-hidden="true">
                    <Icon size={19} />
                  </span>
                  <div className="sj-list-main">
                    <strong>{doc.title}</strong>
                    <p>{doc.description}</p>
                  </div>
                  <div className="sj-list-meta">
                    <span className="sj-meta-pill">{doc.type}</span>
                    <span className="sj-meta-pill sj-v">{doc.version}</span>
                    <span className="sj-meta-note">{doc.sizeMb.toLocaleString("id-ID")} MB · {doc.target}</span>
                    <SmartLink className="sj-outline-button sj-tiny" href={OFFICIAL.portal}>
                      Buka <ExternalLink size={13} />
                    </SmartLink>
                  </div>
                </div>
              );
            })}
          </div>

          <Callout tone="info">
            Versi aplikasi terkini: <strong>Dapodik {SITE.appVersion}</strong>. Gunakan hanya berkas
            yang diunduh dari domain resmi — periksa selalu pengumuman rilis sebelum menginstal.
          </Callout>
        </div>
      </section>
    </>
  );
}
