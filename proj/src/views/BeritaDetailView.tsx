"use client";

import { ArrowRight, CalendarDays, ExternalLink, User } from "lucide-react";
import { ArticleMiniCard } from "../components/data/ArticleCard";
import { SmartLink } from "../components/ui/SmartLink";
import { articles, findArticle } from "../data/news";
import { buildPath } from "../config/routes";
import { NotFoundView } from "./NotFoundView";

/** /berita/:slug — detail artikel contoh + terkait + disclaimer demo. */
export function BeritaDetailView({ slug }: { slug: string }) {
  const article = findArticle(slug);
  if (!article) {
    return <NotFoundView reason="Artikel berita yang Anda cari tidak ada pada katalog demonstrasi." />;
  }

  const related = articles.filter((a) => a.slug !== article.slug && a.category === article.category).slice(0, 3);
  const fallbackRelated = articles.filter((a) => a.slug !== article.slug).slice(0, 3);
  const shown = related.length ? related : fallbackRelated;

  return (
    <>
      <div className="sj-article-head">
        <div className="sj-container">
          <div className="sj-crumbs">
            <SmartLink href={buildPath("/berita")}>
              ← Kembali ke Berita
            </SmartLink>
          </div>
          <span className="sj-eyebrow sj-eyebrow-light">BERITA & INFORMASI</span>
          <h1>{article.title}</h1>
          <div className="sj-article-meta">
            <span>
              <CalendarDays size={13} /> {article.date}
            </span>
            <span>
              <User size={13} /> {article.author}
            </span>
            <span className="sj-hero-chip">{article.category}</span>
          </div>
        </div>
      </div>

      <section className="sj-section-block sj-block-top">
        <div className="sj-container sj-news-layout">
          <article className="sj-article-body">
            <figure className="sj-article-figure">
              <img src="/assets/news-dapodik.webp" alt={`Ilustrasi: ${article.title}`} width="1200" height="640" />
              <figcaption>Ilustrasi Data Pokok Pendidikan — aset demonstrasi tampilan.</figcaption>
            </figure>
            {article.body.map((paragraph, index) => (
              <p key={index}>{paragraph}</p>
            ))}
            <div className="sj-callout sj-info sj-article-foot-gap" role="note">
              <ExternalLink size={16} />
              <div>
                Artikel ini contoh konten untuk menguji tampilan. Kunjungi{" "}
                <SmartLink href="https://dapo.kemendikdasmen.go.id/" rel="noopener noreferrer">
                  portal resmi Dapodik
                </SmartLink>{" "}
                untuk informasi sebenarnya.
              </div>
            </div>
          </article>
          <aside className="sj-side-stack" aria-label="Berita terkait">
            <div className="sj-kv-panel">
              <h3 className="sj-secondary-title">BERITA TERKAIT</h3>
              <div className="sj-link-list">
                {shown.map((item) => (
                  <ArticleMiniCard key={item.slug} article={item} />
                ))}
              </div>
            </div>
            <div className="sj-kv-panel">
              <h3 className="sj-secondary-title">LANJUTKAN MENJELAJAH</h3>
              <div className="sj-link-list">
                <SmartLink className="sj-side-item" href={buildPath("/progres")}>Progres Pengiriman Data</SmartLink>
                <SmartLink className="sj-side-item" href={buildPath("/unduh-aplikasi")}>Unduh Aplikasi</SmartLink>
                <SmartLink className="sj-side-item" href={buildPath("/faq")}>Pusat Bantuan (FAQ)</SmartLink>
              </div>
            </div>
          </aside>
        </div>
      </section>
    </>
  );
}
