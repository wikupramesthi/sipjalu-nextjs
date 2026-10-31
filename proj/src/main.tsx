import { StrictMode, useContext } from "react";
import { createRoot } from "react-dom/client";
import { NavContext, NavProvider } from "./lib/nav";
import { resolveRoute } from "./lib/spa-routes";
import { AppShell } from "./components/layout/AppShell";
import "./styles/globals.css";

/**
 * Standalone SPA entry (Vite build / managed Page artifact). The Next.js
 * App Router entry lives in src/app and renders the same views per route.
 */
function SpaContent() {
  const nav = useContext(NavContext);
  return <>{resolveRoute(nav?.path ?? "/")}</>;
}

const root = document.getElementById("root");

if (!root) {
  throw new Error("Missing #root element");
}

createRoot(root).render(
  <StrictMode>
    <NavProvider>
      <AppShell>
        <SpaContent />
      </AppShell>
    </NavProvider>
  </StrictMode>,
);
