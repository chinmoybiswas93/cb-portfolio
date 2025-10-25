import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
  plugins: [vue()],
  root: 'resources',
  server: {
    host: 'localhost',
    port: 5173,
    cors: true,
  },
  build: {
    outDir: '../assets',
    emptyOutDir: false,
    manifest: true,
    rollupOptions: {
      input: {
        admin: resolve(__dirname, 'resources/admin/app.js'),
        frontend: resolve(__dirname, 'resources/frontend/app.js'),
      },
      output: {
        entryFileNames: '[name]/app.js',
        chunkFileNames: '[name]/chunks/[name]-[hash].js',
      },
    },
  },
  resolve: {
    alias: {
      '@': resolve(__dirname, 'resources'),
    },
  },
});