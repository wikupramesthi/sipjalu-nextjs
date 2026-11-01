import { Globe, Mail, Phone } from "lucide-react";
import { buildPath, footerNav } from "../../config/routes";
import { CONTACT, OFFICIAL, SITE } from "../../data/site";
import { SmartLink } from "../ui/SmartLink";

/* Brand glyph sederhana (lucide menghapus ikon brand) — hanya dekorasi tautan. */
function InstagramGlyph() {
  return (
    <svg
      width="16"
      height="16"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      aria-hidden="true"
    >
      <rect x="2" y="2" width="20" height="20" rx="5" />
      <circle cx="12" cy="12" r="4" />
      <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
    </svg>
  );
}
function YoutubeGlyph() {
  return (
    <svg
      width="16"
      height="16"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth="2"
      aria-hidden="true"
    >
      <rect x="2" y="5" width="20" height="14" rx="4" />
      <path d="M10 9l5 3-5 3z" fill="currentColor" stroke="none" />
    </svg>
  );
}

/** Footer mengikuti struktur portal referensi: brand, Tautan, Kontak, Media Sosial. */
export function Footer() {
  return (
    <footer className="sj-footer">
      <div className="sj-container sj-footer-grid">
        <div className="sj-footer-brand">
          <div className="sj-footer-logo">
            <img
              src="/assets/logo-dapodik.png"
              alt="Logo Dapodik"
              width="40"
              height="40"
            />
            <span>
              <strong>Dapodik</strong>
              <small>{SITE.shortTitle}</small>
            </span>
          </div>
          <p>{SITE.tagline}</p>
        </div>

        <div className="sj-footer-col">
          <h4>Tautan</h4>
          <ul>
            {footerNav.map((route) => (
              <li key={route.id}>
                <SmartLink href={buildPath(route.path)}>
                  {route.label}
                </SmartLink>
              </li>
            ))}
          </ul>
        </div>

        <div className="sj-footer-col">
          <h4>Kontak</h4>
          <ul>
            <li>
              <Mail size={14} aria-hidden="true" /> {CONTACT.email}
            </li>
            <li>
              <Phone size={14} aria-hidden="true" /> {CONTACT.hotline}
            </li>
            <li>
              <Globe size={14} aria-hidden="true" /> {CONTACT.domain}
            </li>
          </ul>
          <h4 className="sj-social-title">Media Sosial</h4>
          <div className="sj-social-row">
            <SmartLink
              className="sj-social-btn"
              href={OFFICIAL.instagram}
              aria-label="Instagram Dapodik"
            >
              <InstagramGlyph />
            </SmartLink>
            <SmartLink
              className="sj-social-btn"
              href={OFFICIAL.youtube}
              aria-label="YouTube resmi"
            >
              <YoutubeGlyph />
            </SmartLink>
          </div>
        </div>

        <div className="sj-footer-official">
          <img
            src="/assets/logo-kementerian.svg"
            alt="Logo Kementerian Pendidikan Dasar dan Menengah RI"
          />
          <span>
            Terinspirasi dari portal
            <br />
            Kemendikdasmen RI
          </span>
        </div>
      </div>
      <div className="sj-container sj-footer-bottom">
        <span>© 2026 {SITE.owner} · Hak Cipta Dilindungi (demo).</span>
      </div>
    </footer>
  );
}
