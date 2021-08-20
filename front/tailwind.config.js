const plugin = require('tailwindcss/plugin')

module.exports = {
  darkMode: false, // or 'media' or 'class'
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
      },
      fontSize: {
        0: [0, 0],
      },
      borderRadius: {
        10: '10px',
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
