"use client";

import { PencarianView } from "./PencarianView";
import { useQueryParams } from "../hooks/use-query-params";

/** Reads ?q= client-side (static-export safe) and hands it to the view. */
export function PencarianViewClient() {
  const params = useQueryParams();
  return <PencarianView initialQuery={params.get("q") ?? ""} />;
}
