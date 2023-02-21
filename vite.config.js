import { defineConfig, loadEnv } from 'vite';
import laravel          from 'laravel-vite-plugin';
import react            from '@vitejs/plugin-react';

export default defineConfig(({command, mode}) => {
    const env = loadEnv(mode, process.cwd())

    return {
        define: {
            SHOPIFY_API_KEY: JSON.stringify(env.VITE_SHOPIFY_API_KEY)
        },
        server: {
            host: "0.0.0.0",
            hmr: {
                protocol: "ws",
                host: "localhost"
            }
        },
        plugins: [
            laravel(['resources/js/app.js']),
            // react()
        ]
    }
});
