import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { BeritaDetailView } from "../../../views/BeritaDetailView";
import { articles, findArticle } from "../../../data/news";

export const dynamicParams = false; // semua slug tervalidasi saat build (output: export)

export function generateStaticParams() {
  return articles.map((article) => ({ slug: article.slug }));
}

interface PageProps {
  params: Promise<{ slug: string }>;
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { slug } = await params;
  const article = findArticle(slug);
  if (!article) return { title: "Artikel tidak ditemukan" };
  return {
    title: article.title,
    description: article.excerpt,
    alternates: { canonical: `/berita/${article.slug}/` },
    openGraph: {
      type: "article",
      title: article.title,
      description: article.excerpt,
      publishedTime: article.isoDate,
    },
  };
}

export default async function BeritaDetailPage({ params }: PageProps) {
  const { slug } = await params;
  if (!findArticle(slug)) notFound();
  return <BeritaDetailView slug={slug} />;
}
