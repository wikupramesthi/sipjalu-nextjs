"use client";

import { useMemo } from "react";
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

  const value = useMemo(
    () => ({
      path: pathname,
      navigate: (to: string) => {
        router.push(to);
        window.scrollTo({ top: 0, behavior: "instant" as ScrollBehavior });
      },
    }),
    [router, pathname],
  );

  return <NavContext.Provider value={value}>{children}</NavContext.Provider>;
}
