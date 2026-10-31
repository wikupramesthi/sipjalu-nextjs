import type { ReactNode } from "react";
import { Header } from "./Header";
import { Footer } from "./Footer";

/** Global chrome: skip link, header, main, footer. */
export function AppShell({ children }: { children: ReactNode }) {
  return (
    <>
      <a className="sj-skip-link" href="#konten">
        Lewati ke konten utama
      </a>
      <Header />
      <main id="konten">{children}</main>
      <Footer />
    </>
  );
}
