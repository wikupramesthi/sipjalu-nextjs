"use client";

import { useEffect, useRef } from "react";
import type { ReactNode } from "react";
import { X } from "lucide-react";
import { useDialogBehaviour } from "../../hooks/use-dialog";

interface ModalProps {
  open: boolean;
  title: string;
  eyebrow?: string;
  symbol?: ReactNode;
  onClose: () => void;
  children: ReactNode;
}

/** Accessible dialog: focus trap (useDialogBehaviour), Escape, click-outside. */
export function Modal({ open, title, eyebrow, symbol, onClose, children }: ModalProps) {
  const closeRef = useRef<HTMLButtonElement | null>(null);
  const modalRef = useRef<HTMLElement | null>(null);
  useDialogBehaviour(open, closeRef, modalRef);

  useEffect(() => {
    if (!open) return;
    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") onClose();
    };
    document.addEventListener("keydown", onKey);
    return () => document.removeEventListener("keydown", onKey);
  }, [open, onClose]);

  if (!open) return null;
  return (
    <div className="sj-modal-backdrop" onClick={onClose}>
      <section
        ref={modalRef}
        className="sj-modal"
        role="dialog"
        aria-modal="true"
        aria-label={title}
        onClick={(event) => event.stopPropagation()}
      >
        <button className="sj-modal-close sj-icon-button" ref={closeRef} onClick={onClose} aria-label="Tutup dialog">
          <X size={21} />
        </button>
        <div className="sj-modal-symbol">{symbol}</div>
        {eyebrow ? <span className="sj-eyebrow">{eyebrow}</span> : null}
        <h2>{title}</h2>
        {children}
      </section>
    </div>
  );
}
