module.exports = {
  root: true,
  parser: 'vue-eslint-parser',
  parserOptions: {
    parser: '@babel/eslint-parser',
    ecmaVersion: 2018,
    sourceType: 'module'
  },
  extends: [
    'plugin:vue/recommended',
    'standard'
  ],
  rules: {
    'vue/max-attributes-per-line': 'off',
    'vue/first-attribute-linebreak': 'off',
    'vue/valid-attribute-name': 'off',
    'vue/no-child-content': 'off',
    'vue/no-reserved-props': 'off',
    'vue/no-v-text-v-html-on-component': 'off',
    'vue/valid-model-definition': 'off'
  }
}
