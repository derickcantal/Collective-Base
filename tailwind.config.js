import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import tailwindcss from 'tailwindcss';

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./src/**/*.{js,ts,jsx,tsx,mdx}",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms'),
      require('flowbite/plugin')
    ],
}