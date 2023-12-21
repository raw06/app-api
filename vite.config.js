/* eslint-disable no-undef */
import { defineConfig, loadEnv } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig(({ mode }) => {
  // Load app-level env vars to node-level env vars.
  process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };

  return defineConfig({
    plugins: [react()],
    // base: '/dist/',
    build: {
      manifest: true,
      input: 'resources/js/index.jsx',
      outDir: 'public/dist',
      copyPublicDir: false
    },
    experimental: {
      renderBuiltUrl(filename) {
        return `${process.env.VITE_CDN_BASE_SCRIPT_TAG}/dist/${filename}`;
      }
    }
  });
});
