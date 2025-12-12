import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        proxy: {
            "/api": {
                target: "http://niche-laravel-app.test",
                changeOrigin: true,
            },
            "/thesis": {
                target: "http://niche-laravel-app.test",
                changeOrigin: true,
            },
            "/submissions": {
                target: "http://niche-laravel-app.test",
                changeOrigin: true,
            },
        },
    },
});
