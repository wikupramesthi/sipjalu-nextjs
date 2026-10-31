import { CalendarDays, Flame } from "lucide-react";
import { buildPath } from "../../config/routes";
import { articles } from "../../data/news";
import type { Article } from "../../types";
import { SmartLink } from "../ui/SmartLink";

/**
 * Strip berita beranda — kartu besar "Berita Utama" di kiri + empat kartu
 * ramping di kanan (struktur grid portal referensi).
 */
export function NewsPreview() {
  const featured: Article = articles.find((a) => a.featured) ?? articles[0];
  const rest = articles.filter((a) => a.slug !== featured.slug).slice(0, 4);

  return (
    <div className="sj-news-showcase">
      <SmartLink className="sj-news-card sj-feature" href={buildPath("/berita", featured.slug)}>
        <div className="sj-news-image">
          <img src="/assets/news-dapodik.webp" alt={`Ilustrasi: ${featured.title}`} loading="lazy" width="720" height="400" />
          <span className="sj-ribbon"><Flame size={11} /> Berita Utama</span>
        </div>
        <div className="sj-news-body">
          <span className="sj-news-category">{featured.category}</span>
          <h3>{featured.title}</h3>
          <p>{featured.excerpt}</p>
          <span className="sj-news-date"><CalendarDays size={13} /> {featured.date}</span>
        </div>
      </SmartLink>
      <div className="sj-news-list">
        {rest.map((article) => (
          <SmartLink className="sj-news-card sj-compact" key={article.slug} href={buildPath("/berita", article.slug)}>
            <img src="/assets/news-dapodik.webp" alt="" loading="lazy" width="96" height="72" />
            <div>
              <span className="sj-news-category">{article.category}</span>
              <h3>{article.title}</h3>
              <span className="sj-news-date"><CalendarDays size={12} /> {article.date}</span>
            </div>
          </SmartLink>
        ))}
      </div>
    </div>
  );
}
