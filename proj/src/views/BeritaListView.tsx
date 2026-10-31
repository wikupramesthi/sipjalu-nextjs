"use client";

import { useState } from "react";
import { ArrowRight, ExternalLink } from "lucide-react";
import { ArticleCard, ArticleMiniCard } from "../components/data/ArticleCard";
import { Chip } from "../components/ui/Chip";
import { PageHero } from "../components/ui/PageHero";
import { SmartLink } from "../components/ui/SmartLink";
import { articles, newsCategories } from "../data/news";
import { OFFICIAL } from "../data/site";

type Filter = "Semua" | (typeof newsCategories)[number];

/** /berita — daftar berita contoh dengan filter kategori + panel populer. */
export function BeritaListView() {
  const [filter, setFilter] = useState<Filter>("Semua");

  const shown = filter === "Semua" ? articles : articles.filter((a) => a.category === filter);
  const countOf = (category: Filter) =>
    category === "Semua" ? articles.length : articles.filter((a) => a.category === category).length;

  return (
    <>
      <PageHero
        eyebrow="INFORMASI & PEMBARUAN"
        title="Berita & Informasi Dapodik"
        description="Pengumuman, jadwal pemutakhiran, rilis aplikasi, dan panduan seputar Data Pokok Pendidikan. Seluruh konten pada demonstrasi ini adalah contoh."
        crumbs={[{ label: "Berita" }]}
        stats={[
          { label: "Total Artikel", value: String(articles.length), note: "data contoh" },
          { label: "Kategori", value: String(newsCategories.length), note: "diperbarui berkala" },
        ]}
      />
      <section className="sj-section-block">
        <div className="sj-container">
          <div className="sj-chip-row" role="group" aria-label="Filter kategori berita">
            {(["Semua", ...newsCategories] as Filter[]).map((category) => (
              <Chip key={category} label={category} count={countOf(category)} active={filter === category} onClick={() => setFilter(category)} />
            ))}
          </div>
          <div className="sj-news-layout">
            <div>
              <div className="sj-news-list">
                {shown.length ? (
                  <div className="sj-news-grid">
                    {shown.map((article) => (
                      <ArticleCard key={article.slug} article={article} />
                    ))}
                  </div>
                ) : (
                  <div className="sj-empty-result">
                    Belum ada artikel pada kategori ini di katalog demonstrasi.
                    <br />
                    <br />
                    <button className="sj-text-button" type="button" onClick={() => setFilter("Semua")}>
                      Lihat semua kategori <ArrowRight size={14} />
                    </button>
                  </div>
                )}
              </div>
            </div>
            <aside className="sj-side-stack" aria-label="Artikel pilihan">
              <div className="sj-kv-panel">
                <h3>
                  <ExternalLink size={14} /> Pengumuman resmi
                </h3>
                <p className="sj-side-note">
                  Pengumuman lengkap dirilis melalui portal resmi Kemendikdasmen. Kunjungi untuk
                  memastikan jadwal dan persyaratan mutakhir.
                </p>
                <SmartLink className="sj-primary-button sj-block-cta" href={OFFICIAL.portal}>
                  Buka portal resmi <ArrowRight size={15} />
                </SmartLink>
              </div>
              <div className="sj-kv-panel">
                <h3>Baca juga</h3>
                {articles
                  .filter((a) => a.slug !== shown[0]?.slug)
                  .slice(0, 3)
                  .map((article) => (
                    <ArticleMiniCard key={article.slug} article={article} />
                  ))}
              </div>
            </aside>
          </div>
        </div>
      </section>
    </>
  );
}
