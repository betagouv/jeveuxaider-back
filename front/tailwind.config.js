const plugin = require('tailwindcss/plugin')

module.exports = {
  darkMode: false, // or 'media' or 'class'
  mode: 'jit',
  purge: {
    content: [
      './components/**/*.{vue,js,ts}',
      './mixins/**/*.{vue,js,ts}',
      './layouts/**/*.vue',
      './pages/**/*.vue',
      './plugins/**/*.{js,ts}',
      './nuxt.config.{js,ts}',
    ],
  },
  theme: {
    screens: {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1412px',
    },
    fontFamily: {
      sans: [
        'Marianne',
        '-apple-system',
        'BlinkMacSystemFont',
        '"Segoe UI"',
        'Roboto',
        '"Helvetica Neue"',
        'Arial',
        '"Noto Sans"',
        'sans-serif',
        '"Apple Color Emoji"',
        '"Segoe UI Emoji"',
        '"Segoe UI Symbol"',
        '"Noto Color Emoji"',
      ],
    },
    extend: {
      colors: {
        primary: '#070191',
        secondary: '#6A6F85',
        solidarite: '#e02424',
        nature: '#057a55',
        education: '#1f0391',
        sante: '#553c9a',
        domaine: {
          solidarite: '#F46D66',
          nature: '#0B9B6B',
          education: '#D42EB0',
          sante: '#61198D',
          covid: '#070191',
          prevention: '#05D3AB',
          sport: '#EE6018',
          culture: '#F99E2D',
          memoire: '#175AB6',
          cooperation: '#5B8397',
        },
        jva: {
          green: '#09AC8C',
          grayLight: '#F9F8F6',
        },
      },
      fontSize: {
        0: [0, 0],
        '1-5xl': '1.375rem',
        '2-5xl': '1.75rem',
      },
      maxWidth: {
        '1/3': '33.3%',
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    plugin(function ({ addUtilities }) {
      const newUtilities = {
        '.will-change-transform': {
          'will-change': 'transform',
        },
      }

      addUtilities(newUtilities, ['responsive', 'hover'])
    }),
  ],
}
