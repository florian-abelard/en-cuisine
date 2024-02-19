/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './components/**/*.{js,vue,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './plugins/**/*.{js,ts}',
    './app.vue',
  ],
  theme: {
    extend: {},
    fontFamily: {
      sans: ['Lato', 'sans-serif'],
      serif: ['Merriweather', 'serif'],
    },
  },
  plugins: [require('daisyui')],
  darkMode: false, // or 'media' or 'class'
  daisyui: {
    themes: [
     {
        bumblebee: {
         ...require("daisyui/src/theming/themes")["[data-theme=bumblebee]"],
         'primary-focus': '#eec707 ',
        },
      },
    ],
  },
};
