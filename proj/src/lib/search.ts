/**
 * Local search utilities.
 *
 * There is no backend in this demo; schools are matched with plain substring
 * comparison (no `new RegExp(userInput)`), which is inherently safe against
 * ReDoS. Inputs are length-capped before matching.
 */

import type { School } from "../types";
import { sanitizeInput } from "./security";

export const MAX_QUERY_LENGTH = 80;
export const MIN_QUERY_LENGTH = 2;

export function normalizeQuery(raw: string): string {
  return sanitizeInput(raw, MAX_QUERY_LENGTH).trim().toLowerCase();
}

export function searchSchools(schools: School[], rawQuery: string): School[] {
  const query = normalizeQuery(rawQuery);
  if (query.length < MIN_QUERY_LENGTH) return [];
  return schools.filter((school) =>
    `${school.name} ${school.npsn} ${school.city} ${school.district} ${school.province} ${school.level}`
      .toLowerCase()
      .includes(query),
  );
}
