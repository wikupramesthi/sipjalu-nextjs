import { ArrowRight, BookMarked, Download } from "lucide-react";
import { buildPath } from "../../config/routes";
import { SITE } from "../../data/site";
import { SmartLink } from "../ui/SmartLink";

/**
 * Strip "Pembaruan Aplikasi" ala portal: dua kartu aksi (unduh aplikasi &
 * lihat panduan) di atas latar gradien dengan dekorasi map samar.
 */
export function PembaruanTiles() {
  return (
    <section className="sj-pembaruan" id="pembaruan">
      <div className="sj-pembaruan-glow" aria-hidden="true" />
      <div className="sj-container">
        <div className="sj-section-top sj-stack sj-light">
          <span className="sj-eyebrow sj-eyebrow-light">Pembaruan Aplikasi</span>
          <h2>Pembaruan Aplikasi Dapodik</h2>
          <p className="sj-muted-light">Informasi terkini seputar aplikasi Dapodik {SITE.owner}.</p>
        </div>
        <div className="sj-pembaruan-grid">
          <div className="sj-p-tile sj-featured">
            <span className="sj-p-tile-icon"><Download size={22} /></span>
            <h3>Aplikasi Terbaru</h3>
            <p>Unduh versi terbaru aplikasi Dapodik dengan fitur yang lebih optimal dan stabil.</p>
            <SmartLink className="sj-primary-button sj-light-cta" href={buildPath("/unduh-aplikasi")}>
              Unduh Aplikasi <ArrowRight size={15} />
            </SmartLink>
          </div>
          <div className="sj-p-tile">
            <span className="sj-p-tile-icon"><BookMarked size={22} /></span>
            <h3>Panduan Aplikasi</h3>
            <p>Dokumentasi lengkap dan tutorial penggunaan aplikasi Dapodik untuk operator sekolah.</p>
            <SmartLink className="sj-outline-button" href={buildPath("/panduan")}>
              Lihat Panduan <ArrowRight size={15} />
            </SmartLink>
          </div>
        </div>
      </div>
    </section>
  );
}
