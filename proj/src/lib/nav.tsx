import { createContext, useCallback, useEffect, useMemo, useState } from "react";
import type { ReactNode } from "react";

/**
 * Framework-independent navigation layer.
 *
 * The same component tree is consumed by the Next.js App Router (server routes
 * in `app/`) and by the Vite single-page build (`src/App.tsx`). To keep the
 * shared UI free of `next/link` imports, navigation goes through this tiny
 * context: providers push/pop the current path, links call `navigate()` and
 * fall back to a plain anchor so the markup works without JavaScript too.
 */

export interface NavState {
  /** Current pathname, e.g. "/berita/pembaruan-2027b". */
  path: string;
  /** Whether a route change is in progress. */
  isNavigating: boolean;
  /** Navigate without a full reload when a provider supports it. */
  navigate: (to: string) => void;
}

const NavContext = createContext<NavState | null>(null);

export { NavContext };

export function NavProvider({ children }: { children: ReactNode }) {
  // The provider is only mounted by the browser bundles (Vite SPA); reading
  // the initial path keeps deep links from flashing the home view.
  const [path, setPath] = useState(() =>
    typeof window === "undefined" ? "/" : window.location.pathname || "/",
  );
  const [isNavigating, setIsNavigating] = useState(false);

  useEffect(() => {
    const sync = () => {
      const nextPath = window.location.pathname || "/";
      if (nextPath !== path) {
        setIsNavigating(true);
        setPath(nextPath);
      }
    };
    window.addEventListener("popstate", sync);
    return () => window.removeEventListener("popstate", sync);
  }, [path]);

  useEffect(() => {
    if (!isNavigating) return;
    const timer = window.setTimeout(() => setIsNavigating(false), 350);
    return () => window.clearTimeout(timer);
  }, [isNavigating, path]);

  const navigate = useCallback((to: string) => {
    const next = to.startsWith("/") ? to : `/${to}`;
    const onlyTarget = next.split("?")[0];
    const previous = window.location.pathname;
    if (previous !== onlyTarget) setIsNavigating(true);
    window.history.pushState({}, "", next);
    if (previous === onlyTarget) {
      // Same route, different query/hash: notify param readers directly.
      window.dispatchEvent(new Event("hashchange"));
      return;
    }
    setPath(onlyTarget);
    window.scrollTo({ top: 0, behavior: "instant" as ScrollBehavior });
  }, []);

  const value = useMemo(
    () => ({ path, isNavigating, navigate }),
    [path, isNavigating, navigate],
  );
  return <NavContext.Provider value={value}>{children}</NavContext.Provider>;
}
