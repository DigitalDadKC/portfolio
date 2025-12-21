import 'vuetify/styles';
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
const colors = require("tailwindcss/colors")

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        fontFamily: {
            primary: ['Graphik', 'sans-serif'],
            body: ['Merriweather', 'serif'],
        },
        container: {
            padding: {
                DEFAULT: "1rem",
                lg: "3rem",
            },
        },
        extend: {
            colors: {
                // "light-primary": "#F3EEEA",
                // "light-secondary": "#EBE3D5",
                // "light-tertiary": "#B0A695",
                // "light-quatrenary": "#776B5D",
                // "dark-primary": "#152A38",
                // "dark-secondary": "#29435C",
                // "dark-tertiary": "#556E53",
                // "dark-quatrenary": "#D1D4C9",
                // accent: {
                //     DEFAULT: "#ac6b34",
                //     hover: "#925a2b"
                // },
                paragraph: "#878e99",
                blue: colors.blue,
                indigo: colors.indigo,
                green: colors.green,
                red: colors.red
            }
        }
    },

    plugins: [forms],
};
