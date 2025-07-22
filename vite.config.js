import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5137,
        strictPort: true,
        cors: true,
        origin: 'http://localhost:5137',
        watch: {
            usePolling: true,
            interval: 100,
            ignored: [
                '**/node_modules/**',
                '**/vendor/**',
                '**/storage/**',
            ],
        },
        hmr: {
            protocol: 'ws',
            // host: 'host.docker.internal', // Usar no windows OS
            port: 5137,
            clientPort: 5137,
        },
    },
    build: {
        sourcemap: false,
    },
});
