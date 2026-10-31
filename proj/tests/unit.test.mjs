/**
 * Unit tests for pure functions in src/lib (Node 22 strips TS types).
 * Run together with the artifact smoke test via: npm test
 */
import assert from "node:assert/strict";
import test from "node:test";

import { buildCsv, csvCell } from "../src/lib/csv.ts";
import { buildPath, routes } from "../src/config/routes.ts";
import { normalizeQuery, searchSchools } from "../src/lib/search.ts";
import {
  isExternalHref,
  parseNpsn,
  safeExternalHref,
  safeInternalPath,
  sanitizeInput,
} from "../src/lib/security.ts";
import { slugify } from "../src/lib/format.ts";
import { schools } from "../src/data/schools.ts";
import { articles, findArticle } from "../src/data/news.ts";

test("csvCell: formula-injection prefixes and quoting", () => {
  assert.ok(csvCell("=HYPERLINK(\"http://evil\")").startsWith("\"'="));
  assert.ok(csvCell("=SUM(1+1)").startsWith("\"'="));
  assert.ok(csvCell("+cmd").startsWith("\"'+"));
  assert.ok(csvCell("-2").startsWith("\"'-2"));
  assert.ok(csvCell("@ME").startsWith("\"'@"));
  assert.equal(csvCell(42), '"42"');
  assert.equal(csvCell('say "hi"'), '"say ""hi"""');
  assert.equal(csvCell("normal"), '"normal"');
});

test("buildCsv: BOM + header + escaped rows", () => {
  const csv = buildCsv(["A", "B"], [["x", 2], ["=1+1", "q"]], "preamble");
  assert.ok(csv.startsWith("\uFEFF"));
  assert.ok(csv.split("\n").some((line) => line.replace(/^\uFEFF/, "") === "preamble"));
  assert.match(csv, /"A","B"/);
  assert.match(csv, /"'=1\+1","q"/);
});

test("buildPath: absolute trailing-slash route builder", () => {
  assert.equal(buildPath("/"), "/");
  assert.equal(buildPath("/berita"), "/berita/");
  assert.equal(buildPath("/", "berita"), "/berita/");
  assert.equal(buildPath("/progres", "32", "3273"), "/progres/32/3273/");
  assert.equal(buildPath("/ikd", "jawa-barat/kota-bandung"), "/ikd/jawa-barat/kota-bandung/");
});

test("registry shape sanity", () => {
  assert.ok(Object.values(routes).every((r) => r.id && r.path.startsWith("/") && r.label));
  assert.ok(routes.home.inHeader === true);
});

test("search: normalization + bounded input + results", () => {
  assert.equal(normalizeQuery("  BanDung  "), "bandung");
  assert.equal(normalizeQuery("x".repeat(300)).length, 80);
  assert.equal(searchSchools(schools, "b").length, 0); // below min length
  assert.ok(searchSchools(schools, "bandung").length >= 2);
});

test("security: external allow-list, internal guards, NPSN", () => {
  assert.ok(safeExternalHref("https://dapo.kemendikdasmen.go.id/"));
  assert.equal(safeExternalHref("http://dapo.kemendikdasmen.go.id/"), undefined);
  assert.equal(safeExternalHref("javascript:alert(1)"), undefined);
  assert.equal(safeExternalHref("https://evil.example/"), undefined);
  assert.equal(isExternalHref("/faq/"), false);
  assert.equal(safeInternalPath("//evil.example"), "/");
  assert.equal(safeInternalPath("/berita/"), "/berita/");
  assert.equal(parseNpsn("20100188"), "20100188");
  assert.equal(parseNpsn("201001889"), undefined);
  assert.equal(parseNpsn("2010018"), undefined);
  assert.equal(parseNpsn("201001888"), undefined);
  assert.equal(parseNpsn(undefined), undefined);
  assert.equal(sanitizeInput("abc\u0007def"), "abcdef");
});

test("format: slugify", () => {
  assert.equal(slugify("Rilis Aplikasi 2027.b!"), "rilis-aplikasi-2027-b");
  assert.equal(slugify("Évaluasi Data"), "evaluasi-data");
});

test("news catalogue: unique slugs, detail lookup works", () => {
  const slugs = articles.map((a) => a.slug);
  assert.equal(new Set(slugs).size, slugs.length);
  assert.ok(findArticle(slugs[0]));
  assert.equal(findArticle("tidak-ada"), undefined);
});
