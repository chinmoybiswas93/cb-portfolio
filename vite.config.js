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
        'frontend/projects': resolve(__dirname, 'resources/frontend/projects.js'),
        'frontend/spotlight': resolve(__dirname, 'resources/frontend/spotlight.js'),
      },
      output: {
        entryFileNames: (chunkInfo) => {
          // Keep admin and frontend as directories with app.js
          if (chunkInfo.name === 'admin' || chunkInfo.name === 'frontend') {
            return '[name]/app.js';
          }
          // frontend/projects becomes frontend/projects.js
          if (chunkInfo.name === 'frontend/projects') {
            return 'frontend/projects.js';
          }
          // frontend/spotlight becomes frontend/spotlight.js
          if (chunkInfo.name === 'frontend/spotlight') {
            return 'frontend/spotlight.js';
          }
          return '[name].js';
        },
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