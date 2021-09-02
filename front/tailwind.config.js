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
      './nuxt.config.js',
      './nuxt.config.ts',
    ],
    options: {
      // Whitelisting some classes to avoid purge
      safelist: {
        standard: [/nature$/, /solidarite$/, /education$/, /sante$/],
      },
    },
  },
  theme: {
    screens: {
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
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
      serif: ['Georgia', 'Cambria', '"Times New Roman"', 'Times', 'serif'],
      mono: [
        'Menlo',
        'Monaco',
        'Consolas',
        '"Liberation Mono"',
        '"Courier New"',
        'monospace',
      ],
      montserrat: ['Montserrat', 'sans-serif'],
    },
    extend: {
      colors: {
        primary: '#070191',
        secondary: '#6A6F85',
        solidarite: '#e02424',
        nature: '#057a55',
        education: '#1f0391',
        sante: '#553c9a',
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
