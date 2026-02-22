import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/css/login.css', 'resources/js/login.js','resources/css/dashboard.css','resources/js/dashboard.js','resources/js/master_pasien.js',
                'resources/css/registrasi.css',
'resources/js/registrasi.js','resources/css/bpjs.css'
,'resources/js/bpjs.js'
            ],
            refresh: true,
        }),
    ],
});
