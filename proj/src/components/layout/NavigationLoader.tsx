"use client";

import { useContext } from "react";
import { NavContext } from "../../lib/nav";

export function NavigationLoader() {
  const navigation = useContext(NavContext);
  if (!navigation?.isNavigating) return null;

  return (
    <div
      className="sj-navigation-loader"
      role="progressbar"
      aria-label="Memuat halaman"
      aria-valuetext="Sedang memuat halaman"
    >
      <span />
    </div>
  );
}
