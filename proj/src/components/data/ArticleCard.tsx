import { ArrowRight, CalendarDays } from "lucide-react";
import { buildPath } from "../../config/routes";
import type { Article } from "../../types";
import { SmartLink } from "../ui/SmartLink";

/** News grid card, shared by home preview (/berita list and detail "related"). */
export function ArticleCard({ article }: { article: Article }) {
  return (
    <SmartLink className="sj-news-card" href={buildPath("/berita", article.slug)}>
      <div className="sj-news-image">
        <img src="/assets/news-dapodik.webp" alt={`Ilustrasi: ${article.title}`} loading="lazy" width="640" height="360" />
        <span>{article.category}</span>
        <div className="sj-news-arrow">
          <ArrowRight size={20} />
        </div>
      </div>
      <div className="sj-news-body">
        <span className="sj-news-date">
          <CalendarDays size={13} />
          {article.date}
        </span>
        <h3>{article.title}</h3>
        <p>{article.excerpt}</p>
        <span className="sj-read-more">
          Baca selengkapnya <ArrowRight size={14} />
        </span>
      </div>
    </SmartLink>
  );
}

/** Small teaser used in blog sidebars. */
export function ArticleMiniCard({ article }: { article: Article }) {
  return (
    <SmartLink className="sj-mini-news" href={buildPath("/berita", article.slug)}>
      <img src="/assets/news-dapodik.webp" alt="" loading="lazy" width="64" height="52" />
      <span>
        <strong>{article.title}</strong>
        <small>{article.category} · {article.date}</small>
      </span>
    </SmartLink>
  );
}
