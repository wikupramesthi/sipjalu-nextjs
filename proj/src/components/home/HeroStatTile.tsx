"use client";

import { GraduationCap, School, Users } from "lucide-react";
import type { ComponentType } from "react";
import { useEffect, useState } from "react";
import { useCountUp } from "../../hooks/use-count-up";

const ICONS: Record<string, ComponentType<{ size?: number }>> = { school: School, graduate: GraduationCap, users: Users };

interface props {
  label: string;
  value: string;
  icon: string;
  tone: "brand" | "deep" | "soft";
  delay: number;
}

/**
 * Tile statistik melayang di hero. Nilai di-decode ke angka untuk animasi
 * count-up; bila tidak dapat diparse, ditampilkan apa adanya.
 */
export function HeroStatTile({ label, value, icon, tone, delay }: props) {
  const Icon = ICONS[icon] ?? School;
  const [mounted, setMounted] = useState(false);
  useEffect(() => setMounted(true), []);
  const reveal = mounted ? "tile-in" : "tile-pre";

  return (
    <div
      className={`sj-hero-stat sj-${tone} sj-${reveal}`}
      style={{ ["--tile-delay" as string]: `${delay}ms` }}
    >
      <div className="sj-hero-stat-icon">
        <Icon size={20} />
      </div>
      <div>
        <span>{label}</span>
        <strong><CountedValue value={value} /></strong>
      </div>
    </div>
  );
}

function CountedValue({ value }: { value: string }) {
  const numeric = Number(value.replace(/[^\d]/g, ""));
  if (!numeric || value.includes("Jt")) return <>{value}</>;
  return <CountUp target={numeric} suffix={value.replace(/[\d.]/g, "")} />;
}

function CountUp({ target, suffix }: { target: number; suffix: string }) {
  const { ref, display } = useCountUp(target);
  // useCountUp mengembalikan angka mentah; format titikThousands id-ID:
  const text = new Intl.NumberFormat("id-ID").format(display);
  return <span ref={ref as React.RefObject<HTMLSpanElement>}>{text}{suffix}</span>;
}
