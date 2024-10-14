/** @type {import('tailwindcss').Config} */
module.exports = {

  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    './assets/**/*.css',
    './public/**/*.html',
  ],
  theme: {
    extend: {
      fontSize: {
        'x': ['1.20rem', '1.70rem'], // 20px with line-height 28px
      },
      width: {
        'xgg': '1000px',
        '800': '800px'
      },
      colors: {
        'bro': '#ff49db',
      },
    },
  },
  plugins: [
    require('flowbite/plugin') // add the flowbite plugin
  ],
}

