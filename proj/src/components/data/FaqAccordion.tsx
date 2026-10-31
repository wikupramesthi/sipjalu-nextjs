"use client";

import { CircleHelp, ChevronDown } from "lucide-react";

export interface FaqItem {
  id: string;
  question: string;
  answer: string;
  category?: string;
}

interface FaqAccordionProps {
  items: readonly FaqItem[];
  openId: string | null;
  onToggle: (id: string) => void;
  /** "text" = underline style (beranda), "panel" = card style (pusat bantuan). */
  variant?: "text" | "panel";
}

/** Single-open accordion. Each item is a button + hidden region pair. */
export function FaqAccordion({ items, openId, onToggle, variant = "panel" }: FaqAccordionProps) {
  return (
    <div className={variant === "panel" ? "sj-accordion-list" : "sj-plain-list"}>
      {items.map((item) => {
        const open = openId === item.id;
        return (
          <div className={`sj-faq-item${open ? " sj-expanded" : ""}`} key={item.id}>
            <button
              type="button"
              aria-expanded={open}
              aria-controls={`faq-answer-${item.id}`}
              onClick={() => onToggle(open ? "" : item.id)}
            >
              {variant === "panel" ? (
                <span className="sj-faq-question">
                  {item.category ? <small>{item.category}</small> : null}
                  <span>{item.question}<CircleHelp size={13} /></span>
                </span>
              ) : item.question}
              <ChevronDown size={18} />
            </button>
            <div id={`faq-answer-${item.id}`} role="region" hidden={!open}>
              <p>{item.answer}</p>
            </div>
          </div>
        );
      })}
    </div>
  );
}
