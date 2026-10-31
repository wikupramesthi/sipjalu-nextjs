export const dynamic = "force-static";

import type { MetadataRoute } from "next";
import { SITE_URL } from "../config/env";

export default function robots(): MetadataRoute.Robots {
  return {
    rules: { userAgent: "*", allow: "/", disallow: ["/sekolah/", "/429/"] },
    sitemap: `${SITE_URL.replace(/\/+$/, "")}/sitemap.xml`,
  };
}
