"use client";

import { useContext, useState } from "react";
import { ArrowRight, Search } from "lucide-react";
import { buildPath } from "../../config/routes";
import { NavContext } from "../../lib/nav";
import { normalizeQuery, MAX_QUERY_LENGTH } from "../../lib/search";

interface SearchFormProps {
  initialQuery?: string;
  /** Called with the validated query; used for on-page filtering. */
  onSubmit?: (query: string) => void;
  placeholder?: string;
}

/**
 * Search box shared by the hero and the /pencarian page. The raw term is
 * sanitized client-side; on submit the app navigates to
 * `/pencarian/?q=<term>` so results stay linkable/bookmarkable.
 */
export function SearchForm({ initialQuery = "", onSubmit, placeholder }: SearchFormProps) {
  const nav = useContext(NavContext);
  const [query, setQuery] = useState(initialQuery);

  return (
    <form
      className="sj-search-box"
      role="search"
      action={buildPath("/pencarian")}
      onSubmit={(event) => {
        event.preventDefault();
        const term = normalizeQuery(query);
        if (!term) return;
        if (onSubmit) {
          onSubmit(term);
          return;
        }
        const target = `${buildPath("/pencarian")}?q=${encodeURIComponent(term)}`;
        if (nav?.navigate) nav.navigate(target);
        else window.location.assign(target);
      }}
    >
      <Search size={20} />
      <input
        name="q"
        value={query}
        onChange={(event) => setQuery(event.target.value)}
        maxLength={MAX_QUERY_LENGTH}
        aria-label="Cari informasi PPJ, wilayah, atau data penerimaan"
        placeholder={placeholder ?? "Cari informasi PPJ, wilayah, atau data penerimaan…"}
        required
      />
      <button type="submit">
        Cari <ArrowRight size={17} />
      </button>
    </form>
  );
}
