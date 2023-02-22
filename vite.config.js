import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd())

    return {
        // Defining Shopify API key to use it in the app.js
        define: {
            __SHOPIFY_API_KEY: JSON.stringify(env.VITE_SHOPIFY_API_KEY)
        },
        plugins: [
            laravel({
                input: ['resources/js/app.js'],
                refresh: true
            }),
            react()
        ]
    }
});
