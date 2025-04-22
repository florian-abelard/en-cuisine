require("@rushstack/eslint-patch/modern-module-resolution");

module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
  },
  extends: [
    'eslint:recommended',
    'plugin:vue/vue3-strongly-recommended',
    '@vue/eslint-config-typescript/recommended',
  ],
  plugins: [
    'vue',
  ],
  rules: {
    'vue/multi-word-component-names': 0,
    'comma-dangle': ['error', 'always-multiline'],
    'semi': ['error', 'always'],
    'quote-props': ['error', 'consistent-as-needed'],
    'vue/max-attributes-per-line': ['error', {
      singleline: 2,
      multiline: 1,
     }],
    'vue/html-self-closing': ['error', {
      html: {
        void: 'always',
        normal: 'always',
        component: 'always',
      },
      svg: 'always',
      math: 'always',
    }],
  },
  globals: {
    $fetch: false,
  },
};
