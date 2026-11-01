import react from "@vitejs/plugin-react";
import { defineConfig, loadEnv } from "vite";

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), "");
  const apiBaseUrl =
    env.NEXT_PUBLIC_API_BASE_URL ||
    env.VITE_API_BASE_URL ||
    (mode === "development" ? "http://127.0.0.1:8000/api" : "");
  const apiKey = env.NEXT_PUBLIC_API_KEY || env.VITE_PUBLIC_API_KEY || "";

  return {
    plugins: [react()],
    define: {
      "process.env.NEXT_PUBLIC_API_BASE_URL": JSON.stringify(apiBaseUrl),
      "process.env.NEXT_PUBLIC_API_KEY": JSON.stringify(apiKey),
    },
    server: {
      allowedHosts: [".muleusercontent.com"],
    },
    build: {
      emptyOutDir: true,
      outDir: "dist",
    },
  };
});
