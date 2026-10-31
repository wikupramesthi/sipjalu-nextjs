import type { Metadata, Viewport } from "next";
import "../styles/globals.css";
import { NavBridge } from "./nav-bridge";
import { AppShell } from "../components/layout/AppShell";
import { SITE } from "../data/site";
import { SITE_URL } from "../config/env";

/** Canonical origin for metadata; overridden via NEXT_PUBLIC_SITE_URL when deployed. */
const siteUrl = SITE_URL;

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: {
    default: `${SITE.title} | Demo`,
    template: `%s | Portal Dapodik (Demo)`,
  },
  description: SITE.description,
  applicationName: "Portal Dapodik Demo",
  authors: [{ name: SITE.owner }],
  keywords: ["dapodik", "data pokok pendidikan", "demonstrasi", "next.js", "typescript"],
  alternates: { canonical: "/" },
  openGraph: {
    type: "website",
    locale: "id_ID",
    url: siteUrl,
    siteName: "Portal Dapodik — Demonstrasi",
    title: SITE.title,
    description: SITE.description,
  },
  twitter: {
    card: "summary_large_image",
    title: SITE.title,
    description: SITE.description,
  },
  robots: {
    index: false, // demo — jangan terindeks menggantikan portal asli
    follow: true,
    googleBot: { index: false, follow: true },
  },
  icons: {
    icon: [
      { url: "/icon.svg", type: "image/svg+xml" },
    ],
  },
};

export const viewport: Viewport = {
  themeColor: "#123b32",
  colorScheme: "light",
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="id">
      <body>
        <NavBridge>
          <AppShell>{children}</AppShell>
        </NavBridge>
      </body>
    </html>
  );
}
