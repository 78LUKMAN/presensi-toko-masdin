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
        host: "0.0.0.0",
        hmr: {
            host: "10.22.66.179",
        },
        port: 5173,
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
