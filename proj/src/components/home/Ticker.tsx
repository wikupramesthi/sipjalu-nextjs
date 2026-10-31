"use client";

import { Pause, Play } from "lucide-react";
import { useState } from "react";
import { notices } from "../../data/announcements";

/**
 * Bar "Info Terkini" — marquee horizontal berjalan otomatis seperti portal
 * (dua salinan isi agar loop mulus). Tombol jeda menghentikan animasi; klik
 * isi membuka dialog carousel pengumuman.
 */
export function Ticker({ onOpen }: { onOpen: () => void }) {
  const [paused, setPaused] = useState(false);
  const items = [...notices, ...notices];

  return (
    <div className="sj-ticker">
      <div className="sj-ticker-inner">
        <button className="sj-ticker-lead" onClick={onOpen} aria-label="Buka info terkini">
          Info Terkini
          <span className="sj-ticker-tag">PENGUMUMAN</span>
        </button>
        <div 
          className={`sj-ticker-viewport${paused ? " sj-paused" : ""}`} 
          role="marquee" 
          aria-label="Pengumuman berjalan" 
          data-paused={paused}
          onClick={onOpen}
          style={{ cursor: "pointer" }}
        >
          <div className="sj-ticker-track">
            {items.map((notice, i) => (
              <span 
                className={`sj-ticker-item sj-${notice.kind.toLowerCase()}`} 
                key={`${notice.id}-${i}`}
                onClick={(e) => {
                  e.stopPropagation();
                  onOpen();
                }}
                style={{ cursor: "pointer" }}
              >
                <em>{notice.kind}</em>
                {notice.text}
                <b aria-hidden="true">•</b>
              </span>
            ))}
          </div>
        </div>
        <button className="sj-ticker-pause" onClick={(e) => { e.stopPropagation(); setPaused((v) => !v); }} aria-pressed={paused} aria-label={paused ? "Lanjutkan info" : "Jeda info"}>
          {paused ? <Play size={14} /> : <Pause size={14} />}
        </button>
      </div>
    </div>
  );
}