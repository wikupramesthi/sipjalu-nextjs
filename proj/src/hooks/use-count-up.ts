import { useEffect, useRef, useState } from "react";

/**
 * Count-up animation seperti beranda portal referensi.
 * SSR-safe: render pertama selalu nilai final (tidak ada hydration mismatch);
 * animasi berjalan setelah mount saat elemen terlihat viewport.
 */
export function useCountUp(target: number, durationMs = 1400) {
  const ref = useRef<HTMLElement | null>(null);
  const [display, setDisplay] = useState(target);

  useEffect(() => {
    const el = ref.current;
    if (!el || target === 0) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

    let raf = 0;
    let start = 0;
    const observer = new IntersectionObserver(
      (entries) => {
        if (!entries[0]?.isIntersecting) return;
        observer.disconnect();
        setDisplay(0);
        const step = (now: number) => {
          if (!start) start = now;
          const p = Math.min(1, (now - start) / durationMs);
          const eased = 1 - (1 - p) ** 3;
          setDisplay(Math.round(target * eased));
          if (p < 1) raf = requestAnimationFrame(step);
        };
        raf = requestAnimationFrame(step);
      },
      { threshold: 0.4 },
    );
    observer.observe(el);
    return () => {
      observer.disconnect();
      cancelAnimationFrame(raf);
    };
  }, [target, durationMs]);

  return { ref, display };
}
