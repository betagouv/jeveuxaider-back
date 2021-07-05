module.exports = {
  env: {
    browser: true,
    node: true,
    es2021: true,
  },
  extends: ['@nuxtjs', 'plugin:nuxt/recommended', 'prettier'],
  parserOptions: {
    parser: 'babel-eslint',
  },
  plugins: ['prettier'],
  rules: {
    'no-console': 'off',
    eqeqeq: 'off',
    'vue/no-v-html': 'off',
    'vue/html-closing-bracket-newline': 'off',
    'vue/html-self-closing': 'off',
    'prettier/prettier': 'error',
  },
}
