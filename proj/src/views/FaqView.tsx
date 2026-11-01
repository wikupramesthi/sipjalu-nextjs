"use client";

import { useEffect, useMemo, useState } from "react";
import {
  AlertCircle,
  ChevronLeft,
  ChevronRight,
  Search,
  X,
} from "lucide-react";
import { Breadcrumbs } from "../components/ui/Breadcrumbs";
import { FaqAccordion } from "../components/data/FaqAccordion";
import { fetchFaqs, getFaqCategories } from "../lib/faq-api";
import type { FaqEntry } from "../types";

type Tab = string;
const PAGE_SIZE = 10;

/** /faq — pusat bantuan yang datanya dibaca dari API backend. */
export function FaqView() {
  const [tab, setTab] = useState<Tab>("Semua");
  const [term, setTerm] = useState("");
  const [openId, setOpenId] = useState<string | null>(null);
  const [page, setPage] = useState(1);
  const [faqs, setFaqs] = useState<FaqEntry[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [hasError, setHasError] = useState(false);
  const [retryCount, setRetryCount] = useState(0);

  useEffect(() => {
    const controller = new AbortController();
    setIsLoading(true);
    setHasError(false);

    fetchFaqs(controller.signal, { refresh: retryCount > 0 })
      .then((entries) => {
        setFaqs(entries);
        setPage(1);
        setOpenId(null);
      })
      .catch((error: unknown) => {
        if (error instanceof Error && error.name === "AbortError") return;
        setHasError(true);
      })
      .finally(() => {
        if (!controller.signal.aborted) setIsLoading(false);
      });

    return () => controller.abort();
  }, [retryCount]);

  const categories = useMemo(() => getFaqCategories(faqs), [faqs]);

  const filtered = useMemo(() => {
    const query = term.trim().toLowerCase();
    return faqs
      .filter((entry) => tab === "Semua" || entry.category === tab)
      .filter(
        (entry) =>
          !query ||
          `${entry.question} ${entry.answer} ${entry.category}`
            .toLowerCase()
            .includes(query),
      );
  }, [faqs, tab, term]);
  const pageCount = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  const pageItems = filtered.slice((page - 1) * PAGE_SIZE, page * PAGE_SIZE);
  const firstItem = filtered.length ? (page - 1) * PAGE_SIZE + 1 : 0;
  const lastItem = Math.min(page * PAGE_SIZE, filtered.length);
  const selectedCount =
    tab === "Semua"
      ? faqs.length
      : faqs.filter((faq) => faq.category === tab).length;

  return (
    <div className="sj-faq-page">
      <section className="sj-faq-hero">
        <div className="sj-container sj-faq-hero-inner">
          <Breadcrumbs
            items={[{ label: "Beranda", href: "/" }, { label: "FAQ" }]}
          />
          <div className="sj-faq-hero-title">
            <div>
              <h1>Pusat Bantuan SIPJALU</h1>
              <p>
                Temukan jawaban seputar pengelolaan data wajib pajak, pelanggan
                listrik, potensi pajak, pembayaran, pengendalian, dan pelaporan
                penerimaan.
              </p>
            </div>
          </div>
          <form
            className="sj-faq-search"
            role="search"
            onSubmit={(event) => event.preventDefault()}
          >
            <Search size={17} aria-hidden="true" />
            <input
              value={term}
              onChange={(event) => {
                setTerm(event.target.value);
                setPage(1);
                setOpenId(null);
              }}
              maxLength={120}
              placeholder="Cari pertanyaan atau kata kunci..."
              aria-label="Cari pertanyaan atau kata kunci"
            />
            {term ? (
              <button
                type="button"
                onClick={() => {
                  setTerm("");
                  setPage(1);
                  setOpenId(null);
                }}
                aria-label="Hapus pencarian"
              >
                <X size={16} />
              </button>
            ) : null}
          </form>
        </div>
      </section>

      <section className="sj-faq-content">
        <div className="sj-container sj-faq-layout">
          <section className="sj-faq-topics" aria-labelledby="faq-topics-title">
            <div className="sj-faq-topics-heading">
              <span id="faq-topics-title">JELAJAHI TOPIK</span>
              <p>Pilih bidang untuk mempersempit pertanyaan</p>
            </div>
            {isLoading ? (
              <p className="sj-faq-category-loading" role="status">
                Memuat kategori...
              </p>
            ) : (
              <nav className="sj-faq-topic-list" aria-label="Topik FAQ">
                {(["Semua", ...categories] as Tab[]).map((category) => {
                  const count =
                    category === "Semua"
                      ? faqs.length
                      : faqs.filter((faq) => faq.category === category).length;
                  return (
                    <button
                      key={category}
                      className={
                        tab === category
                          ? "sj-faq-category sj-active"
                          : "sj-faq-category"
                      }
                      onClick={() => {
                        setTab(category);
                        setPage(1);
                        setOpenId(null);
                      }}
                      aria-pressed={tab === category}
                    >
                      <span>{category}</span>
                      <span className="sj-faq-count">{count}</span>
                    </button>
                  );
                })}
              </nav>
            )}
          </section>

          <div className="sj-faq-results">
            <div className="sj-faq-results-head">
              <div>
                <span className="sj-faq-section-kicker">
                  PENGETAHUAN &amp; LAYANAN
                </span>
                <h2>
                  {tab === "Semua" ? "Pertanyaan yang sering diajukan" : tab}
                </h2>
              </div>
              <p className="sj-faq-result-count">
                {isLoading
                  ? "Memuat FAQ dari server..."
                  : hasError
                    ? "API FAQ tidak tersedia."
                    : filtered.length
                      ? `${firstItem}–${lastItem} dari ${filtered.length} pertanyaan`
                      : `Tidak ada pertanyaan untuk "${term || tab}"`}
              </p>
            </div>
            {isLoading ? (
              <div className="sj-faq-empty" role="status" aria-live="polite">
                <span className="sj-faq-loading-spinner" aria-hidden="true" />
                <strong>Memuat pertanyaan...</strong>
                <p>Data FAQ sedang diambil dari server.</p>
              </div>
            ) : hasError ? (
              <div className="sj-faq-empty sj-faq-api-error" role="alert">
                <span aria-hidden="true">
                  <AlertCircle size={20} />
                </span>
                <div className="sj-faq-api-error-copy">
                  <strong>Data belum tersedia</strong>
                  <p>Gagal memuat data FAQ. Silakan coba lagi.</p>
                </div>
                <button
                  type="button"
                  onClick={() => setRetryCount((count) => count + 1)}
                >
                  Coba lagi
                </button>
              </div>
            ) : filtered.length ? (
              <>
                <p className="sj-faq-list-caption">
                  <span>{selectedCount}</span> jawaban tersedia
                </p>
                <FaqAccordion
                  key={`${tab}-${term}`}
                  items={pageItems.map(
                    ({ id, question, answer, category }) => ({
                      id,
                      question,
                      answer,
                      category,
                    }),
                  )}
                  openId={openId}
                  onToggle={(id) => setOpenId(id || null)}
                />
              </>
            ) : (
              <div className="sj-faq-empty">
                <span>
                  <Search size={21} />
                </span>
                <strong>
                  {faqs.length
                    ? "Tidak ditemukan hasil pencarian."
                    : "Belum ada pertanyaan FAQ."}
                </strong>
                <p>
                  {faqs.length
                    ? "Coba kata kunci lain atau pilih kategori untuk menemukan jawaban yang Anda butuhkan."
                    : "Belum ada pertanyaan aktif yang tersedia saat ini."}
                </p>
                {faqs.length ? (
                  <button
                    type="button"
                    onClick={() => {
                      setTerm("");
                      setTab("Semua");
                      setPage(1);
                      setOpenId(null);
                    }}
                  >
                    Reset pencarian
                  </button>
                ) : null}
              </div>
            )}
            {pageCount > 1 ? (
              <nav
                className="sj-faq-pagination"
                aria-label="Navigasi halaman pertanyaan"
              >
                <span>
                  Halaman {page} dari {pageCount}
                </span>
                <div>
                  <button
                    type="button"
                    onClick={() => {
                      setPage((current) => Math.max(1, current - 1));
                      setOpenId(null);
                    }}
                    disabled={page === 1}
                  >
                    <ChevronLeft size={14} /> Sebelumnya
                  </button>
                  {Array.from(
                    { length: pageCount },
                    (_, index) => index + 1,
                  ).map((pageNumber) => (
                    <button
                      key={pageNumber}
                      type="button"
                      className={page === pageNumber ? "sj-current" : ""}
                      aria-current={page === pageNumber ? "page" : undefined}
                      onClick={() => {
                        setPage(pageNumber);
                        setOpenId(null);
                      }}
                    >
                      {pageNumber}
                    </button>
                  ))}
                  <button
                    type="button"
                    onClick={() => {
                      setPage((current) => Math.min(pageCount, current + 1));
                      setOpenId(null);
                    }}
                    disabled={page === pageCount}
                  >
                    Berikutnya <ChevronRight size={14} />
                  </button>
                </div>
              </nav>
            ) : null}
          </div>
        </div>
      </section>
    </div>
  );
}
