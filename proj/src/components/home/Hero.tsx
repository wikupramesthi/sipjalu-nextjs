"use client";

import { CalendarClock, ShieldCheck } from "lucide-react";
import { SITE } from "../../data/site";
import { formatDate, nf } from "../../lib/format";
import { SearchForm } from "./SearchForm";
import { FloatingBubbles } from "./FloatingBubbles";
import { HeroStatTile } from "./HeroStatTile";
import { useReveal } from "../../hooks/use-reveal";

/**
 * Hero meniru Portal Dapodik: eyebrow badge, judul besar, deskripsi, search
 * (navigasi ke /pencarian/?q=), baris "Pembaruan terakhir", kolom tile
 * statistik melayang dengan animasi count-up di atas gelembung dekoratif.
 */
export function Hero() {
  const copy = useReveal<HTMLDivElement>(0);
  const visual = useReveal<HTMLDivElement>(120);

  return (
    <section className="sj-hero" id="beranda">
      <div className="sj-hero-vignette" aria-hidden="true" />
      <FloatingBubbles />
      <div className="sj-container sj-hero-inner">
        <div ref={copy.ref} className={`sj-hero-copy ${copy.className}`}>
          <div className="sj-hero-badge">
            <ShieldCheck size={13} />
            <span>{SITE.heroEyebrow}</span>
            <span className="sj-badge-dot" />
          </div>
          <h1>
            Data Pokok Pendidikan
            <br />
            <span>
              Akurat, Mutakhir dan
              <br />
              Berkelanjutan.
            </span>
          </h1>
          <p className="sj-hero-description">{SITE.heroDescription}</p>
          <SearchForm />
          <div className="sj-hero-meta">
            <CalendarClock size={14} />
            <span>Pembaruan terakhir: {formatDate(SITE.lastSync)}</span>
          </div>
        </div>
        <div ref={visual.ref} className={`sj-hero-visual ${visual.className}`}>
          <div className="sj-map-label">
            <span className="sj-live-dot" />
            Memastikan akurasi pendataan
          </div>
          <HeroStatTile label="Total Sekolah" value={nf(447812)} icon="school" tone="brand" delay={0} />
          <HeroStatTile label="Peserta Didik" value="53.1 Jt" icon="graduate" tone="deep" delay={140} />
          <HeroStatTile label="Guru & Tendik" value="4.4 Jt" icon="users" tone="soft" delay={280} />
        </div>
      </div>
    </section>
  );
}
