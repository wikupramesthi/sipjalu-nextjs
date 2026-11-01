import type { ReactNode } from "react";
import { Header } from "./Header";
import { Footer } from "./Footer";
import { NavigationLoader } from "./NavigationLoader";

/** Global chrome: skip link, header, main, footer. */
export function AppShell({ children }: { children: ReactNode }) {
  return (
    <>
      <NavigationLoader />
      <a className="sj-skip-link" href="#konten">
        Lewati ke konten utama
      </a>
      <Header />
      <main id="konten">{children}</main>
      <Footer />
    </>
  );
}
