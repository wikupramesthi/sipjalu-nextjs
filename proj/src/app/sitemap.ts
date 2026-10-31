export const dynamic = "force-static";

import type { MetadataRoute } from "next";
import { articles } from "../data/news";
import { provinceProgress } from "../data/progress";
import { ikdDistricts, ikdProvinces } from "../data/ikd";
import { sitemapPaths } from "../config/routes";
import { SITE_URL } from "../config/env";

/**
 * Static sitemap generated at build time — only routes that actually exist in
 * this demo catalogue are published. Robots noindex stays on the whole site
 * (demo), but the sitemap remains useful for local search tooling.
 */
export default function sitemap(): MetadataRoute.Sitemap {
  const base = SITE_URL.replace(/\/+$/, "");
  const now = new Date();
  const paths = new Set<string>([...sitemapPaths]);
  for (const article of articles) paths.add(`/berita/${article.slug}/`);
  for (const province of provinceProgress) {
    paths.add(`/progres/${province.kode}/`);
    for (const district of province.districts) {
      paths.add(`/progres/${province.kode}/${district.kode}/`);
      for (const sub of district.subdistricts) paths.add(`/progres/${province.kode}/${district.kode}/${sub.kode}/`);
    }
  }
  for (const province of ikdProvinces) {
    paths.add(`/ikd/${province.slug}/`);
    for (const district of ikdDistricts) {
      if (district.parents?.slug === province.slug) paths.add(`/ikd/${province.slug}/${district.slug}/`);
    }
  }
  return [...paths].map((path) => ({
    url: `${base}${path === "/" ? "/" : path.replace(/^\//, "").replace(/\/+$/, "")}/`,
    lastModified: now,
    changeFrequency: "monthly",
    priority: path === "/" ? 1 : 0.6,
  }));
}
