import { useEffect, useState } from "react";

/**
 * Reads URL query parameters on the client only (safe for static export and
 * for the Vite build; nothing is read during server prerender). Reacts to
 * browser back/forward through popstate and to same-route pushes through
 * hashchange (see NavProvider). No dependency on next/navigation keeps the
 * shared views framework-agnostic.
 */
export function useQueryParams(): URLSearchParams {
  const [params, setParams] = useState<URLSearchParams>(
    () => new URLSearchParams(typeof window === "undefined" ? "" : window.location.search),
  );

  useEffect(() => {
    const sync = () => setParams(new URLSearchParams(window.location.search));
    window.addEventListener("popstate", sync);
    window.addEventListener("hashchange", sync);
    return () => {
      window.removeEventListener("popstate", sync);
      window.removeEventListener("hashchange", sync);
    };
  }, []);

  return params;
}

/** Re-renders when the route changes (popstate / SPA navigation). */
export function usePathChange(handler: () => void) {
  useEffect(() => {
    window.addEventListener("popstate", handler);
    window.addEventListener("hashchange", handler);
    return () => {
      window.removeEventListener("popstate", handler);
      window.removeEventListener("hashchange", handler);
    };
  }, [handler]);
}
