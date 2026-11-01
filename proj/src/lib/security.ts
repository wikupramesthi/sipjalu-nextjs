/**
 * Security helpers.
 *
 * Threat model notes for this static portal:
 * - Only the public FAQ endpoint makes a network request. CSP
 *   `connect-src` (see `docs/SECURITY.md`) restricts the allowed backend
 *   origin; no credentials or user-entered data are sent.
 * - Outbound links only point at an explicit allow-list of government
 *   domains; everything else falls back to safe, in-app routes.
 * - Text coming from the URL (search terms, NPSN query params) is treated as
 *   untrusted: length-limited, control characters stripped, and never used to
 *   build URLs or markup (`React` escaping already covers DOM XSS; these
 *   guards cover CSV export and redirects).
 */

/** Allowed hosts for outbound links (exact host match). */
const EXTERNAL_ALLOWLIST: ReadonlyArray<string> = [
  "dapo.kemendikdasmen.go.id",
  "verifier.data.kemendikdasmen.go.id",
  "data.kemendikdasmen.go.id",
  "rangkaikan.data.kemendikdasmen.go.id",
  "tanya.data.kemendikdasmen.go.id",
  "referensi.data.kemendikdasmen.go.id",
  "litbang.data.kemendikdasmen.go.id",
  "pd.data.kemendikdasmen.go.id",
  "www.instagram.com",
  "instagram.com",
  "www.youtube.com",
  "youtube.com",
];

/** Attributes applied to every `_blank` external link. */
export const EXTERNAL_LINK_PROPS = {
  target: "_blank",
  rel: "noopener noreferrer",
} as const;

export function isExternalHref(href: string): boolean {
  return /^https?:\/\//i.test(href);
}

/** Returns `href` only when it points to https + an allow-listed government
 *  host; otherwise returns undefined so callers can render a safe fallback. */
export function safeExternalHref(href: string): string | undefined {
  let url: URL;
  try {
    url = new URL(href);
  } catch {
    return undefined;
  }
  if (url.protocol !== "https:") return undefined;
  return EXTERNAL_ALLOWLIST.includes(url.hostname) ? href : undefined;
}

/** Blocks any non-absolute internal path (open-redirect guard). */
export function safeInternalPath(to: string): string {
  return to.startsWith("/") && !to.startsWith("//") ? to : "/";
}

/** Truncates and strips control characters from untrusted text input. */
export function sanitizeInput(raw: string, maxLength = 120): string {
  // eslint-disable-next-line no-control-regex
  return raw.replace(/[\u0000-\u001f\u007f]/g, "").slice(0, maxLength);
}

/** NPSN is exactly 8 digits; anything else is rejected instead of rendered. */
export function parseNpsn(raw: string | null | undefined): string | undefined {
  const value = typeof raw === "string" ? raw.trim() : "";
  return /^\d{8}$/.test(value) ? value : undefined;
}
