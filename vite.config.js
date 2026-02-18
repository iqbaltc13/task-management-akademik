import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),
    ],
<<<<<<< HEAD
    build: {
    chunkSizeWarningLimit: 1000
  }
    
=======
   build: {
    chunkSizeWarningLimit: 1600,
   },
>>>>>>> 88cbf930d14dbc12e6fc78cc5d5bd7b4518155ef
});

