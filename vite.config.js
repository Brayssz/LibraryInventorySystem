import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

import html from '@rollup/plugin-html';
import { glob } from 'glob';
function GetFilesArray(query) {
    return glob.sync(query);
}

const pageJsFiles = GetFilesArray("resources/assets/js/*.js");
const CssFiles = GetFilesArray("resources/assets/css/*.css");


export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                ...pageJsFiles,
                ...CssFiles,
            ],
            refresh: true,
        }),
        tailwindcss(),
        html()
    ],
});