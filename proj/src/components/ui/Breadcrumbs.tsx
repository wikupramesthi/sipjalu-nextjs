import { ChevronRight } from "lucide-react";
import { Fragment } from "react";
import { SmartLink } from "./SmartLink";

export interface Crumb {
  label: string;
  href?: string;
}

/** SEO-friendly breadcrumb trail. Last entry renders as current page. */
export function Breadcrumbs({ items }: { items: Crumb[] }) {
  return (
    <nav className="sj-breadcrumbs" aria-label="Jalur navigasi">
      <ol>
        {items.map((item, i) => (
          <Fragment key={`${item.label}-${i}`}>
            {i > 0 ? <li aria-hidden="true"><ChevronRight size={12} /></li> : null}
            <li>
              {item.href && i < items.length - 1 ? (
                <SmartLink href={item.href}>{item.label}</SmartLink>
              ) : (
                <span aria-current={i === items.length - 1 ? "page" : undefined}>{item.label}</span>
              )}
            </li>
          </Fragment>
        ))}
      </ol>
    </nav>
  );
}
