module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  extends: [
    "eslint:recommended",
    "plugin:vue/vue3-strongly-recommended",
  ],
  plugins: [
    'vue'
  ],
  rules: {
    'vue/multi-word-component-names': 0,
  }
}
