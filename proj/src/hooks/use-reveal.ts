import { useEffect, useRef, useState } from "react";

/**
 * Scroll-reveal (fade-up saat masuk viewport) ala animasi beranda portal.
 * Awal: hidden=false di render server (konten tetap terlihat tanpa JS);
 * jika motion diizinkan dan observer tersedia, elemen di-hide lalu di-reveal.
 */
export function useReveal<T extends HTMLElement>(delayMs = 0) {
  const ref = useRef<T | null>(null);
  const [state, setState] = useState<"none" | "idle" | "in">("none");

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      setState("in");
      return;
    }
    setState("idle");
    const observer = new IntersectionObserver(
      (entries) => {
        if (!entries[0]?.isIntersecting) return;
        observer.disconnect();
        window.setTimeout(() => setState("in"), delayMs);
      },
      { threshold: 0.15 },
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, [delayMs]);

  const className = state === "idle" ? "sj-reveal-hidden" : state === "in" ? "sj-reveal-shown" : "";
  return { ref, className };
}
