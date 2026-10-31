"use client";

import { useMemo, useState } from "react";
import { ChevronLeft, ChevronRight, CircleHelp, Search, X } from "lucide-react";
import { Breadcrumbs } from "../components/ui/Breadcrumbs";
import { FaqAccordion } from "../components/data/FaqAccordion";
import { faqCategories, faqs } from "../data/faqs";

type Tab = "Semua" | (typeof faqCategories)[number];
const PAGE_SIZE = 10;

/** /faq — pusat bantuan dengan pencarian lokal + kategori. */
export function FaqView() {
  const [tab, setTab] = useState<Tab>("Semua");
  const [term, setTerm] = useState("");
  const [openId, setOpenId] = useState<string | null>(null);
  const [page, setPage] = useState(1);

  const filtered = useMemo(() => {
    const query = term.trim().toLowerCase();
    return faqs
      .filter((entry) => tab === "Semua" || entry.category === tab)
      .filter((entry) => !query || `${entry.question} ${entry.answer} ${entry.category}`.toLowerCase().includes(query));
  }, [tab, term]);
  const pageCount = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  const pageItems = filtered.slice((page - 1) * PAGE_SIZE, page * PAGE_SIZE);
  const firstItem = filtered.length ? (page - 1) * PAGE_SIZE + 1 : 0;
  const lastItem = Math.min(page * PAGE_SIZE, filtered.length);

  return (
    <div className="sj-faq-page">
      <section className="sj-faq-hero">
        <div className="sj-container sj-faq-hero-inner">
          <Breadcrumbs items={[{ label: "Beranda", href: "/" }, { label: "FAQ" }]} />
          <div className="sj-faq-hero-title">
            <span className="sj-faq-hero-icon"><CircleHelp size={20} /></span>
            <div>
              <h1>Pusat Bantuan Dapodik</h1>
              <p>Temukan jawaban seputar aplikasi Dapodik — instalasi, akun, peserta didik, GTK, sinkronisasi, dan validasi.</p>
            </div>
          </div>
          <form className="sj-faq-search" role="search" onSubmit={(event) => event.preventDefault()}>
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
              <button type="button" onClick={() => { setTerm(""); setPage(1); setOpenId(null); }} aria-label="Hapus pencarian">
                <X size={16} />
              </button>
            ) : null}
          </form>
        </div>
      </section>

      <section className="sj-faq-content">
        <div className="sj-container sj-faq-layout">
          <aside className="sj-faq-sidebar">
            <h2>Kategori</h2>
            <nav aria-label="Kategori bantuan">
              {(["Semua", ...faqCategories] as Tab[]).map((category) => {
                const count =
                  category === "Semua" ? faqs.length : faqs.filter((faq) => faq.category === category).length;
                return (
                  <button
                    key={category}
                    className={tab === category ? "sj-faq-category sj-active" : "sj-faq-category"}
                    onClick={() => {
                      setTab(category);
                      setPage(1);
                      setOpenId(null);
                    }}
                    aria-pressed={tab === category}
                  >
                    <span>{category}</span>
                    <span className="sj-faq-count">{count}</span>
                    {category !== "Semua" ? <ChevronRight size={14} aria-hidden="true" /> : null}
                  </button>
                );
              })}
            </nav>
          </aside>

          <div className="sj-faq-results">
            <div className="sj-faq-results-head">
              <h2>{tab === "Semua" ? "Semua Pertanyaan" : tab}</h2>
              <p>
                {filtered.length
                  ? `Menampilkan ${firstItem}–${lastItem} dari ${filtered.length} pertanyaan`
                  : `Tidak ada pertanyaan untuk "${term || tab}"`}
              </p>
            </div>
            {filtered.length ? (
              <FaqAccordion
                key={`${tab}-${term}`}
                items={pageItems.map(({ id, question, answer, category }) => ({ id, question, answer, category }))}
                openId={openId}
                onToggle={(id) => setOpenId(id || null)}
              />
            ) : (
              <div className="sj-faq-empty">
                <span><Search size={21} /></span>
                <strong>Tidak ditemukan hasil pencarian.</strong>
                <p>Coba kata kunci lain atau pilih kategori untuk menemukan jawaban yang Anda butuhkan.</p>
                <button type="button" onClick={() => { setTerm(""); setTab("Semua"); setPage(1); setOpenId(null); }}>Reset pencarian</button>
              </div>
            )}
            {pageCount > 1 ? (
              <nav className="sj-faq-pagination" aria-label="Navigasi halaman pertanyaan">
                <span>Halaman {page} dari {pageCount}</span>
                <div>
                  <button type="button" onClick={() => { setPage((current) => Math.max(1, current - 1)); setOpenId(null); }} disabled={page === 1}>
                    <ChevronLeft size={14} /> Sebelumnya
                  </button>
                  {Array.from({ length: pageCount }, (_, index) => index + 1).map((pageNumber) => (
                    <button
                      key={pageNumber}
                      type="button"
                      className={page === pageNumber ? "sj-current" : ""}
                      aria-current={page === pageNumber ? "page" : undefined}
                      onClick={() => { setPage(pageNumber); setOpenId(null); }}
                    >
                      {pageNumber}
                    </button>
                  ))}
                  <button type="button" onClick={() => { setPage((current) => Math.min(pageCount, current + 1)); setOpenId(null); }} disabled={page === pageCount}>
                    Berikutnya <ChevronRight size={14} />
                  </button>
                </div>
              </nav>
            ) : null}
            <p className="sj-faq-demo-note">Informasi pada halaman ini merupakan konten demonstrasi, bukan layanan resmi Dapodik.</p>
          </div>
        </div>
      </section>
    </div>
  );
}
