import { BarChart3, GraduationCap, School, TrendingDown, TrendingUp, Users } from "lucide-react";
import type { ComponentType } from "react";

const ICONS: Record<string, ComponentType<{ size?: number }>> = {
  school: School,
  graduate: GraduationCap,
  users: Users,
  chart: BarChart3,
};

export interface SummaryCardItem {
  label: string;
  value: string;
  growth: string;
  trend: "up" | "down";
  tone: "blue" | "cyan" | "violet" | "green";
  icon: string;
}

/** Four headline cards under "Dapodik dalam Angka". */
export function SummaryCards({ items, semesterNote }: { items: readonly SummaryCardItem[]; semesterNote: string }) {
  return (
    <div className="sj-summary-grid">
      {items.map((item) => {
        const Icon = ICONS[item.icon] ?? School;
        return (
          <article key={item.label} className={`sj-summary-card sj-${item.tone}`}>
            <div className="sj-summary-head">
              <span>{item.label}</span>
              <div className={`sj-summary-icon sj-${item.tone}`}>
                <Icon size={24} />
              </div>
            </div>
            <strong>{item.value}</strong>
            <div className="sj-summary-foot">
              <span className={item.trend === "down" ? "sj-negative" : undefined}>
                {item.trend === "down" ? <TrendingDown size={11} /> : <TrendingUp size={11} />}
                {item.growth}
              </span>
              <small>{semesterNote}</small>
            </div>
          </article>
        );
      })}
    </div>
  );
}
