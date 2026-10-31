"use client";

import { useEffect, useMemo, useState } from "react";
import { Search, SlidersHorizontal } from "lucide-react";
import { PageHero } from "../components/ui/PageHero";
import { Callout } from "../components/ui/Callout";
import { Chip } from "../components/ui/Chip";
import { SearchForm } from "../components/home/SearchForm";
import { SchoolResultCard } from "../components/data/SchoolCard";
import { schools } from "../data/schools";
import { searchSchools, normalizeQuery, MIN_QUERY_LENGTH } from "../lib/search";

const JENJANG_OPTIONS = ["Semua", "PAUD", "SD", "SMP", "SMA", "SMK", "SLB"] as const;
type Jenjang = (typeof JENJANG_OPTIONS)[number];

/** /pencarian — result page, query in URL (?q=) so results are shareable. */
export function PencarianView({ initialQuery = "" }: { initialQuery?: string }) {
  const [query, setQuery] = useState(initialQuery);
  const [jenjang, setJenjang] = useState<Jenjang>("Semua");

  // Keep in sync when the page loads/refreshes with ?q= present.
  useEffect(() => {
    setQuery(normalizeQuery(initialQuery));
  }, [initialQuery]);

  const results = useMemo(
    () =>
      searchSchools(schools, query).filter(
        (school) => jenjang === "Semua" || school.level === jenjang,
      ),
    [query, jenjang],
  );

  return (
    <>
      <PageHero
        eyebrow="PENCARIAN SATUAN PENDIDIKAN"
        title="Temukan sekolah, NPSN, atau wilayah"
        description="Cari pada katalog contoh demonstrasi ini. Cobalah kata Bandung, Jakarta, Yogyakarta, Surabaya, atau NPSN tertentu."
        crumbs={[{ label: "Pencarian" }]}
        aside={
          <div className="sj-search-in-hero">
            <SearchForm initialQuery={query} onSubmit={setQuery} />
          </div>
        }
      />
      <section className="sj-section-block">
        <div className="sj-container">
          <div className="sj-chip-row" role="group" aria-label="Filter jenjang">
            <SlidersHorizontal size={15} />
            {JENJANG_OPTIONS.map((option) => (
              <Chip
                key={option}
                label={option}
                active={jenjang === option}
                onClick={() => setJenjang(option)}
              />
            ))}
          </div>
          <p className="sj-result-count" role="status">
            {query.length < MIN_QUERY_LENGTH ? (
              <>Ketik minimal {MIN_QUERY_LENGTH} huruf untuk mencari.</>
            ) : (
              <>
                Ditemukan <strong>{results.length}</strong> sekolah untuk{" "}
                <strong>“{query}”</strong> pada katalog contoh.
              </>
            )}
          </p>
          {results.length ? (
            <div className="sj-school-results">
              {results.map((school) => (
                <SchoolResultCard key={school.npsn} school={school} />
              ))}
            </div>
          ) : query.length >= MIN_QUERY_LENGTH ? (
            <Callout tone="info">
              <Search size={16} />
              <div>
                Tidak ada hasil. Coba kata <strong>Bandung</strong>, <strong>Jakarta</strong>,{" "}
                <strong>Yogyakarta</strong>, atau <strong>Surabaya</strong>, lalu pastikan filter
                jenjang sesuai.
              </div>
            </Callout>
          ) : null}
        </div>
      </section>
    </>
  );
}
