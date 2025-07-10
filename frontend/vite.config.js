import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0', // ← WAŻNE dla Dockera!
    port: 5173,
    strictPort: true,
    watch: {
      usePolling: true // ← Potrzebne na Docker + Windows
    },
    hmr: {
      protocol: 'ws',
      host: 'localhost', // lub: host.docker.internal
      port: 5173,
    },
  },
});