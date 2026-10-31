import type { ReactNode } from "react";

import { HomeView } from "../views/HomeView";
import { BeritaListView } from "../views/BeritaListView";
import { BeritaDetailView } from "../views/BeritaDetailView";
import { PencarianViewClient } from "../views/PencarianViewClient";
import { SekolahView } from "../views/SekolahView";
import { ProgresView } from "../views/ProgresView";
import { IkdView } from "../views/IkdView";
import { PanduanView } from "../views/PanduanView";
import { FaqView } from "../views/FaqView";
import { UnduhView } from "../views/UnduhView";
import { ThrottledView } from "../views/ThrottledView";
import { NotFoundView } from "../views/NotFoundView";

/**
 * Pure path→view resolver for the SPA (Vite) build. Mirrors the App Router
 * segment structure under src/app so both bundles render the same content.
 * Unknown paths return the 404 view; segments are user-controlled strings
 * and are decoded with safeURIComponent before being handed to the views.
 */
export function resolveRoute(pathname: string): ReactNode {
  const clean = pathname.replace(/\/+$/, "").replace(/^\/+/, "");
  const segs = clean === "" ? [] : clean.split("/").map(decodeSegment);

  const [root, a, b, c] = segs;
  if (segs.length === 0) return <HomeView />;
  switch (root) {
    case "berita":
      if (segs.length === 1) return <BeritaListView />;
      if (segs.length === 2 && a) return <BeritaDetailView slug={a} />;
      return <NotFoundView />;
    case "pencarian":
      return segs.length === 1 ? <PencarianViewClient /> : <NotFoundView />;
    case "sekolah":
      return segs.length === 1 ? <SekolahView /> : <NotFoundView />;
    case "progres":
      if (segs.length === 1) return <ProgresView />;
      if (segs.length === 2) return <ProgresView provinsi={a} />;
      if (segs.length === 3) return <ProgresView provinsi={a} kabupaten={b} />;
      if (segs.length === 4) return <ProgresView provinsi={a} kabupaten={b} kecamatan={c} />;
      return <NotFoundView />;
    case "ikd":
      if (segs.length === 1) return <IkdView />;
      if (segs.length === 2) return <IkdView provinsi={a} />;
      if (segs.length === 3) return <IkdView provinsi={a} kabupaten={b} />;
      return <NotFoundView />;
    case "panduan":
      return segs.length === 1 ? <PanduanView /> : <NotFoundView />;
    case "faq":
      return segs.length === 1 ? <FaqView /> : <NotFoundView />;
    case "unduh-aplikasi":
      return segs.length === 1 ? <UnduhView /> : <NotFoundView />;
    case "429":
      return segs.length === 1 ? <ThrottledView /> : <NotFoundView />;
    default:
      return <NotFoundView />;
  }
}

function decodeSegment(segment: string): string {
  try {
    return decodeURIComponent(segment);
  } catch {
    // Malformed percent-encoding: keep the raw value; views re-validate.
    return segment;
  }
}
