import type { ReactNode } from "react";
import { Breadcrumbs } from "./Breadcrumbs";
import type { Crumb } from "./Breadcrumbs";

export interface StatBandItem {
  label: string;
  value: string;
  note?: ReactNode;
}

interface PageHeroProps {
  eyebrow: string;
  title: string;
  description?: string;
  crumbs: Crumb[];
  stats?: StatBandItem[];
  aside?: ReactNode;
}

/** Blue banner shared by all secondary pages (labelled demonstration copy). */
export function PageHero({ eyebrow, title, description, crumbs, stats = [], aside }: PageHeroProps) {
  return (
    <section className="sj-page-hero">
      <div className="sj-container sj-page-hero-inner">
        <Breadcrumbs items={[{ label: "Beranda", href: "/" }, ...crumbs]} />
        <span className="sj-eyebrow">{eyebrow}</span>
        <h1>{title}</h1>
        {description ? <p className="sj-hero-sub">{description}</p> : null}
        {aside}
        {stats.length ? (
          <div className="sj-stat-band">
            {stats.map((item) => (
              <div key={item.label}>
                <span>{item.label}</span>
                <strong>{item.value}</strong>
                {item.note ? <small>{item.note}</small> : null}
              </div>
            ))}
          </div>
        ) : null}
      </div>
    </section>
  );
}
