import { Info, ShieldAlert, TriangleAlert } from "lucide-react";
import type { ReactNode } from "react";

const ICONS = { info: Info, warn: TriangleAlert, alert: ShieldAlert, danger: ShieldAlert } as const;

/** Contextual notice box. Always used for "demo data" disclaimers. */
export function Callout({ tone = "info", children }: { tone?: keyof typeof ICONS; children: ReactNode }) {
  const Icon = ICONS[tone];
  return (
    <div className={`sj-callout sj-${tone}`} role="note">
      <Icon size={17} />
      <div>{children}</div>
    </div>
  );
}
