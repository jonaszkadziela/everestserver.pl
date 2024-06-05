import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './app/View/Components/**/*.php',
        './database/migrations/**/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './storage/framework/views/*.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],

    theme: {
        extend: {
            minWidth: {
                '64': '16rem',
            },
        },
    },

    plugins: [forms],
}
