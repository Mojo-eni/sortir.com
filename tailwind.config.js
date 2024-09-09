/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')
module.exports = {
  content: [
    "templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'custom-blue': '#144A80',
        'light-blue': '#5698ba',
        'gray-blue':'#56656c',
        'custom-orange': '#ffa155'
      },
      backgroundImage: {
        'bg': "url('C:\\wamp64\\www\\sortir.com\\assets\\images/friends.jpg')",
      }

    },
  },
  plugins: [],
}

