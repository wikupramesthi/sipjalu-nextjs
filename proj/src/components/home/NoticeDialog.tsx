"use client";

import { ChevronLeft, ChevronRight, Megaphone, X } from "lucide-react";
import { useEffect, useRef, useState } from "react";
import { notices } from "../../data/announcements";
import { useDialogBehaviour } from "../../hooks/use-dialog";

const AUTO_MS = 7000;

/**
 * Dialog pengumuman carousel dengan navigasi prev/next, dot, dan rotasi
 * otomatis. Escape dan backdrop tetap bisa menutup (accessibility).
 */
export function NoticeDialog({ open, onClose }: { open: boolean; onClose: () => void }) {
  const [index, setIndex] = useState(0);
  const closeRef = useRef<HTMLButtonElement | null>(null);
  const panelRef = useRef<HTMLElement | null>(null);
  useDialogBehaviour(open, closeRef, panelRef);

  useEffect(() => {
    if (!open) {
      setIndex(0);
      return;
    }
    const rotate = window.setInterval(() => setIndex((i) => (i + 1) % notices.length), AUTO_MS);
    return () => window.clearInterval(rotate);
  }, [open]);

  useEffect(() => {
    if (!open) return;
    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") onClose();
      if (event.key === "ArrowRight") setIndex((i) => (i + 1) % notices.length);
      if (event.key === "ArrowLeft") setIndex((i) => (i - 1 + notices.length) % notices.length);
    };
    document.addEventListener("keydown", onKey);
    return () => document.removeEventListener("keydown", onKey);
  }, [open, onClose]);

  if (!open) return null;
  const notice = notices[index];

  return (
    <div className="sj-notice-backdrop" onClick={onClose}>
      <section
        ref={panelRef}
        className="sj-notice-dialog"
        role="dialog"
        aria-modal="true"
        aria-label={notice.title}
        onClick={(event) => event.stopPropagation()}
      >
        <button className="sj-notice-prev" onClick={() => setIndex((i) => (i - 1 + notices.length) % notices.length)} aria-label="Info sebelumnya">
          <ChevronLeft size={24} />
        </button>
        <div className="sj-notice-panel">
          <div className="sj-notice-header">
            <div className="sj-notice-icon-wrapper">
              <span className="sj-notice-icon"><Megaphone size={22} /></span>
            </div>
            <div className="sj-notice-title-wrap">
              <span className={`sj-notice-kind sj-${notice.kind.toLowerCase()}`}>{notice.kind}</span>
              <h2>{notice.title}</h2>
            </div>
          </div>
          <div className="sj-notice-content">
            {(notice.paragraphs ?? [notice.text]).map((paragraph, i) => (
              <p className="sj-notice-text" key={`${notice.id}-${i}`}>
                {paragraph}
              </p>
            ))}
            <p className="sj-notice-date">Dipublikasikan {notice.published}</p>
          </div>
          <div className="sj-notice-dots" role="tablist" aria-label="Pilih info">
            {notices.map((item, i) => (
              <button
                key={item.id}
                role="tab"
                aria-selected={i === index}
                className={i === index ? "sj-dot sj-active" : "sj-dot"}
                onClick={() => setIndex(i)}
                aria-label={`${item.title}${i === index ? ", dipilih" : ""}`}
              />
            ))}
          </div>
          <div className="sj-notice-footer">
            <button ref={closeRef} className="sj-notice-close-btn" onClick={onClose}>
              <X size={17} />
              <span>Tutup</span>
            </button>
          </div>
        </div>
        <button className="sj-notice-next" onClick={() => setIndex((i) => (i + 1) % notices.length)} aria-label="Info berikutnya">
          <ChevronRight size={24} />
        </button>
      </section>
    </div>
  );
}