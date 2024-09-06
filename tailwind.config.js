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
        'orange': '#e36414'
      },
    },
  },
  plugins: [],
}

