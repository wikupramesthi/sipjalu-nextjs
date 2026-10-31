"use client";

import type { AnchorHTMLAttributes, ReactNode } from "react";
import { useContext } from "react";
import { NavContext } from "../../lib/nav";
import { isExternalHref, safeExternalHref, safeInternalPath } from "../../lib/security";

interface SmartLinkProps extends Omit<AnchorHTMLAttributes<HTMLAnchorElement>, "href"> {
  href: string;
  children: ReactNode;
  /** Force full-document navigation even when a router is mounted. */
  forceDocument?: boolean;
}

/**
 * Navigation anchor used everywhere in the shared UI.
 *
 * - External hrefs: https + allow-list only, target=_blank with
 *   rel="noopener noreferrer"; anything else is refused (renders a span).
 * - Internal hrefs: keep a real href (progressive enhancement / SEO) but use
 *   the mounted router when present, falling back to plain links otherwise.
 *   The component must never import next/link so the same tree builds in the
 *   standalone Vite bundle.
 */
export function SmartLink({ href, children, onClick, forceDocument = false, ...rest }: SmartLinkProps) {
  const nav = useContext(NavContext);

  if (isExternalHref(href)) {
    const safe = safeExternalHref(href);
    if (!safe) return <span className="sj-dead-link">{children}</span>;
    return (
      <a href={safe} target="_blank" rel="noopener noreferrer" {...rest}>
        {children}
      </a>
    );
  }

  const internal = safeInternalPath(href);
  return (
    <a
      href={internal}
      onClick={(event) => {
        onClick?.(event);
        if (forceDocument || event.defaultPrevented) return;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.button !== 0) return;
        if (!nav?.navigate) return;
        event.preventDefault();
        nav.navigate(internal);
      }}
      {...rest}
    >
      {children}
    </a>
  );
}
