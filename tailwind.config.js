/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'custom-blue': '#144A80',
        'light-blue': '#669bbc',
        'custom-orange': '#f48134',
      },
    },
  },
  plugins: [],
}

