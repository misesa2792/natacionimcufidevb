import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/sesmas.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(), 
        tailwindcss(),
    ],
    resolve: {
      alias: {
        // Apunta "vue" a la build que incluye el compilador de templates
        'vue': 'vue/dist/vue.esm-bundler.js',
      },
    }
});
