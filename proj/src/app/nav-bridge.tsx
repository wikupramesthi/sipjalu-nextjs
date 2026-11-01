"use client";

import { useEffect, useMemo, useRef, useState } from "react";
import { usePathname, useRouter } from "next/navigation";
import { NavContext } from "../lib/nav";

/**
 * Bridges Next's App Router into the framework-agnostic NavContext, so the
 * shared Header/SmartLink get soft client-side navigation inside the Next
 * build — while the same components still render plain <a> tags in the
 * standalone Vite bundle (where NavProvider is used instead).
 */
export function NavBridge({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const pathname = usePathname() ?? "/";
  const [isNavigating, setIsNavigating] = useState(false);
  const previousPath = useRef(pathname);

  useEffect(() => {
    if (previousPath.current === pathname) return;
    previousPath.current = pathname;
    setIsNavigating(true);
    const timer = window.setTimeout(() => setIsNavigating(false), 350);
    return () => window.clearTimeout(timer);
  }, [pathname]);

  const value = useMemo(
    () => ({
      path: pathname,
      isNavigating,
      navigate: (to: string) => {
        const targetPath = new URL(to, window.location.origin).pathname;
        if (targetPath !== pathname) setIsNavigating(true);
        router.push(to);
        window.scrollTo({ top: 0, behavior: "instant" as ScrollBehavior });
      },
    }),
    [router, pathname, isNavigating],
  );

  return <NavContext.Provider value={value}>{children}</NavContext.Provider>;
}
