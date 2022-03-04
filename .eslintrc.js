module.exports = {
  env: {
    browser: true,
    es6: true,
    node: true,
  },
  parserOptions: {
    parser: 'babel-eslint',
  },
  extends: [
    // add more generic rulesets here, such as:
    'eslint:recommended',
    'plugin:vue/recommended',
    'prettier/vue',
    'plugin:prettier/recommended',
  ],
  plugins: ['vue'],
  rules: {
    // override/add rules settings here, such as:
    'vue/no-v-html': 'off',
  },
}
