import type { FaqEntry } from "../types";

const FAQ_CACHE_TTL_MS = 60_000;
const FAQ_REQUEST_TIMEOUT_MS = 15_000;
let faqCache: { entries: FaqEntry[]; expiresAt: number } | null = null;

const CATEGORY_LABELS: Readonly<Record<string, string>> = {
  "informasi-umum": "Informasi Umum",
  "data-wajib-pajak": "Data Wajib Pajak",
  "data-pelanggan-listrik": "Data Pelanggan Listrik",
  "potensi-pajak": "Potensi Pajak",
  "target-dan-realisasi": "Target dan Realisasi",
  "pembayaran-pajak": "Pembayaran Pajak",
  "tunggakan-pajak": "Tunggakan Pajak",
  "pengendalian-dan-verifikasi": "Pengendalian dan Verifikasi",
  "laporan-dan-statistik": "Laporan dan Statistik",
  "regulasi-dan-ketentuan": "Regulasi dan Ketentuan",
};

function isRecord(value: unknown): value is Record<string, unknown> {
  return typeof value === "object" && value !== null && !Array.isArray(value);
}

function categoryLabel(value: unknown): string {
  if (typeof value !== "string" || !value.trim()) return "Tanpa Kategori";
  const slug = value.trim().toLowerCase();
  return CATEGORY_LABELS[slug] ?? slug
    .split("-")
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(" ");
}

function answerText(value: string): string {
  if (typeof DOMParser === "undefined") {
    return value
      .replace(/<(script|style|template)\b[^>]*>[\s\S]*?<\/\1\s*>/gi, " ")
      .replace(/<[^>]*>/g, " ")
      .replace(/\s+/g, " ")
      .replace(/\s+([,.;:!?])/g, "$1")
      .trim();
  }

  const document = new DOMParser().parseFromString(value, "text/html");
  document.querySelectorAll("script, style, template").forEach((element) => element.remove());
  document.querySelectorAll("br, p, div, li, h1, h2, h3, h4, h5, h6, tr").forEach((element) => {
    element.before(" ");
    element.after(" ");
  });
  return (document.body.textContent ?? "")
    .replace(/\s+/g, " ")
    .replace(/\s+([,.;:!?])/g, "$1")
    .trim();
}

export function normalizeFaqResponse(payload: unknown): FaqEntry[] {
  if (!isRecord(payload) || payload.status !== "success" || !Array.isArray(payload.data)) {
    throw new Error("Respons API FAQ tidak sesuai format.");
  }

  return payload.data.map((entry, index) => {
    if (!isRecord(entry)) {
      throw new Error(`Data FAQ nomor ${index + 1} tidak valid.`);
    }

    const id = typeof entry.id === "string" ? entry.id : entry.uuid;
    if (
      typeof id !== "string" ||
      typeof entry.question !== "string" ||
      typeof entry.answer !== "string" ||
      (entry.kategori !== null && entry.kategori !== undefined && typeof entry.kategori !== "string")
    ) {
      throw new Error(`Data FAQ nomor ${index + 1} tidak lengkap.`);
    }

    return {
      id,
      question: entry.question,
      answer: answerText(entry.answer),
      category: categoryLabel(entry.kategori),
    };
  });
}

export function getFaqCategories(faqs: readonly FaqEntry[]): string[] {
  return [...new Set(faqs.map((faq) => faq.category))];
}

export async function fetchFaqs(
  signal: AbortSignal,
  options: { refresh?: boolean } = {},
): Promise<FaqEntry[]> {
  if (!options.refresh && faqCache && faqCache.expiresAt > Date.now()) {
    return faqCache.entries;
  }

  const configuredBaseUrl = process.env.NEXT_PUBLIC_API_BASE_URL?.trim();
  const baseUrl = configuredBaseUrl || (
    process.env.NODE_ENV === "development" ? "http://127.0.0.1:8000/api" : ""
  );
  if (!baseUrl) throw new Error("Alamat API belum dikonfigurasi.");

  let endpoint: URL;
  try {
    endpoint = new URL(baseUrl);
  } catch {
    throw new Error("Alamat API FAQ tidak valid.");
  }

  if (!["http:", "https:"].includes(endpoint.protocol) || endpoint.username || endpoint.password) {
    throw new Error("Alamat API FAQ tidak valid.");
  }

  endpoint.pathname = `${endpoint.pathname.replace(/\/+$/, "")}/faqs`;
  endpoint.search = "";
  endpoint.hash = "";

  const apiKey = process.env.NEXT_PUBLIC_API_KEY?.trim();
  const requestController = new AbortController();
  const abortRequest = () => requestController.abort();
  const timeout = setTimeout(abortRequest, FAQ_REQUEST_TIMEOUT_MS);
  if (signal.aborted) abortRequest();
  else signal.addEventListener("abort", abortRequest, { once: true });

  try {
    const response = await fetch(endpoint, {
      method: "GET",
      headers: {
        Accept: "application/json",
        ...(apiKey ? { "X-Api-Key": apiKey } : {}),
      },
      credentials: "omit",
      cache: "no-store",
      signal: requestController.signal,
    });

    if (!response.ok) throw new Error(`API FAQ merespons HTTP ${response.status}.`);
    const entries = normalizeFaqResponse(await response.json());
    faqCache = { entries, expiresAt: Date.now() + FAQ_CACHE_TTL_MS };
    return entries;
  } catch (error) {
    if (!signal.aborted && requestController.signal.aborted) {
      throw new Error("API FAQ terlalu lama merespons. Silakan coba lagi.");
    }
    throw error;
  } finally {
    clearTimeout(timeout);
    signal.removeEventListener("abort", abortRequest);
  }
}
