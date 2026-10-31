"use client";

import { useEffect } from "react";
import { Home, RefreshCw, TriangleAlert } from "lucide-react";

/**
 * Client error boundary for every route. Static export cannot server-render
 * recovery UI, so this must stay a Client Component. Errors are logged to the
 * console only — no data from the error object is ever rendered (keeps stack
 * traces and potential internals out of the DOM).
 */
export default function AppError({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  useEffect(() => {
    console.error("Unhandled UI error (demo):", error.digest ?? error.message);
  }, [error]);

  return (
    <div className="sj-state-page sj-container">
      <div className="sj-state-symbol sj-warn">
        <TriangleAlert size={38} />
      </div>
      <p className="sj-state-code">Terjadi Kesalahan</p>
      <h1>Tampilan gagal dimuat</h1>
      <p className="sj-state-desc">
        Sesuatu tidak beres pada komponen ini. Muat ulang halaman, atau kembali ke beranda bila
        masalah berlanjut.
      </p>
      <div className="sj-state-actions">
        <button className="sj-primary-button" onClick={reset}>
          <RefreshCw size={15} /> Coba lagi
        </button>
        <a className="sj-outline-button" href="/">
          <Home size={15} /> Beranda
        </a>
      </div>
    </div>
  );
}
