"use client";

import { useContext, useEffect, useRef, useState } from "react";
import { ChevronDown, ExternalLink, LogIn, Menu, X } from "lucide-react";
import { buildPath, headerNav } from "../../config/routes";
import { NavContext } from "../../lib/nav";
import { OFFICIAL, SITE } from "../../data/site";
import { SmartLink } from "../ui/SmartLink";

/** Sticky portal header: brand, anchored route nav, official-login note, mobile menu. */
export function Header() {
  const nav = useContext(NavContext);
  const [menuOpen, setMenuOpen] = useState(false);
  const [loginOpen, setLoginOpen] = useState(false);
  const loginRef = useRef<HTMLDivElement | null>(null);
  const path = nav?.path ?? "/";

  // Close menus whenever the route changes (SPA or document navigation).
  useEffect(() => {
    setMenuOpen(false);
    setLoginOpen(false);
  }, [path]);

  useEffect(() => {
    if (!loginOpen) return;
    const onDocClick = (event: MouseEvent) => {
      if (loginRef.current && !loginRef.current.contains(event.target as Node)) setLoginOpen(false);
    };
    const onKey = (event: KeyboardEvent) => event.key === "Escape" && setLoginOpen(false);
    document.addEventListener("mousedown", onDocClick);
    document.addEventListener("keydown", onKey);
    return () => {
      document.removeEventListener("mousedown", onDocClick);
      document.removeEventListener("keydown", onKey);
    };
  }, [loginOpen]);

  const normalized = path.replace(/\/+$/, "") || "/";
  const isActive = (routePath: string) =>
    routePath === "/" ? normalized === "/" : normalized === routePath || normalized.startsWith(`${routePath}/`);
  // Beberapa menu berbagi path; tandai hanya yang pertama agar tidak dobel.
  const activeId = headerNav.find((route) => isActive(route.path))?.id;

  return (
    <header className="sj-header">
      <div className="sj-container sj-header-inner">
        <SmartLink className="sj-brand" href="/" aria-label="Ke beranda Dapodik">
          <img src="/assets/logo-dapodik.png" alt="Logo Dapodik" width="43" height="43" />
          <span>
            <strong>{SITE.shortTitle}</strong>
            <small>{SITE.owner}</small>
          </span>
        </SmartLink>

        <nav aria-label="Navigasi utama" className={menuOpen ? "sj-nav sj-open" : "sj-nav"}>
          {headerNav.map((route) => (
            <SmartLink
              key={route.id}
              href={buildPath(route.path)}
              className={activeId === route.id ? "sj-nav-home" : undefined}
              aria-current={activeId === route.id ? "page" : undefined}
            >
              {route.label}
            </SmartLink>
          ))}
        </nav>

        <div className="sj-login-wrap" ref={loginRef}>
          <button
            className="sj-login-button"
            onClick={() => setLoginOpen((open) => !open)}
            aria-expanded={loginOpen}
            aria-haspopup="true"
          >
            <LogIn size={16} />
            <span>Masuk ke Sistem</span>
            <ChevronDown size={15} />
          </button>
          {loginOpen ? (
            <div className="sj-login-dropdown" role="region" aria-label="Informasi akses layanan resmi">
              <strong>Akses layanan resmi</strong>
              <p>{SITE.demoNotice}</p>
              <SmartLink href={OFFICIAL.portal}>
                Buka portal Dapodik <ExternalLink size={14} />
              </SmartLink>
            </div>
          ) : null}
        </div>

        <button
          className="sj-mobile-toggle"
          onClick={() => setMenuOpen((open) => !open)}
          aria-label={menuOpen ? "Tutup navigasi" : "Buka navigasi"}
          aria-expanded={menuOpen}
        >
          {menuOpen ? <X /> : <Menu />}
        </button>
      </div>
    </header>
  );
}
