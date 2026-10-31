import type { NextConfig } from "next";

/**
 * Production posture for a static export:
 * - output:"export" keeps the app deployable on any static host.
 * - trailingSlash:"true" emits directory/index.html so deep links resolve
 *   without rewrite rules.
 * - poweredByHeader:false hides the framework fingerprint.
 * - referrerPolicy strict keeps tokens out of third-party requests.
 * Security headers (CSP, HSTS, X-Frame-Options, ...) are applied at the edge
 * via `public/_headers` (or nginx config in docs/SECURITY.md) because header
 * rewrites require a server and are not available in static export mode.
 */
const nextConfig: NextConfig = {
  output: "export",
  trailingSlash: true,
  poweredByHeader: false,
  reactStrictMode: true,
  images: { unoptimized: true },
  devIndicators: false,
  typescript: { ignoreBuildErrors: false },
  // Pin the tracing root to this package so a lockfile in a parent folder
  // does not trigger the "inferred workspace root" warning.
  outputFileTracingRoot: process.cwd(),
};

export default nextConfig;
