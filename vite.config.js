import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // Split vendor libraries (axios, etc.) into separate chunk
        splitVendorChunkPlugin(),
    ],
    build: {
        // Use esbuild for minification (faster, default in Vite)
        minify: 'esbuild',
        // Optimize chunk splitting
        rollupOptions: {
            output: {
                // Manual chunk splitting for better caching
                manualChunks: (id) => {
                    // Separate node_modules into vendor chunk
                    if (id.includes('node_modules')) {
                        // Split large libraries into their own chunks
                        if (id.includes('axios')) {
                            return 'vendor-axios';
                        }
                        return 'vendor';
                    }
                },
                // Asset naming for better caching
                chunkFileNames: 'assets/js/[name]-[hash].js',
                entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
            },
        },
        // Set chunk size warning limit
        chunkSizeWarningLimit: 500,
    },
});
