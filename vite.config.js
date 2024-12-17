import { defineConfig } from 'vite';
import * as path from 'path';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Components from 'unplugin-vue-components/vite';
import {PrimeVueResolver} from '@primevue/auto-import-resolver';

export default defineConfig({
    resolve: {
        alias: {
          '@/public': path.resolve(__dirname, 'public') // Replace 'src' with your desired root directory
        }
      },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            resolvers: [
              PrimeVueResolver()
            ]
        })
    ],
});
