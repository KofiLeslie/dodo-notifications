import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/echo.js'], // include any other entry points here
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        rollupOptions: {
            output: {
                format: 'es',       // ensure ES modules
                entryFileNames: '[name].js',
                exports: 'named',   // preserve named exports like initEcho
            },
        },
    },
});
