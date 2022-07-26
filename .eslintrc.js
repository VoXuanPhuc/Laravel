module.exports = {
  root: true,
  env: {
    node: true,
  },
  extends: ["@vue/standard", "@vue/prettier", "plugin:vue/vue3-essential", "eslint:recommended"],
  plugins: [],
  parserOptions: {
    parser: "babel-eslint",
  },
  rules: {
    // "import/extensions": ["never"],
    // General rules
    "no-console": process.env.NODE_ENV === "production" ? "warn" : "warn",
    "no-debugger": process.env.NODE_ENV === "production" ? "error" : "error",
    "no-tabs": ["error", { allowIndentationTabs: true }],
    // quotes: ["error", "single"],
    // indent: ["error", "tab"], // enable this may cause an error: Cannot read property "range" of null

    // Vue rules
    // https://eslint.vuejs.org/rules/max-len.html
    "vue/max-len": [
      "error",
      {
        code: 130,
        template: 130,
        ignoreComments: true,
        ignoreUrls: true,
        ignoreStrings: true,
        ignoreTemplateLiterals: true,
        ignoreRegExpLiterals: true,
        ignoreHTMLAttributeValues: true,
        ignoreHTMLTextContents: true,
      },
    ],

    // https://eslint.vuejs.org/rules/attribute-hyphenation.html
    "vue/attribute-hyphenation": ["error", "never"],

    // https://eslint.vuejs.org/rules/prop-name-casing.html#vue-prop-name-casing
    "vue/prop-name-casing": ["error", "camelCase"],

    // https://eslint.vuejs.org/rules/custom-event-name-casing.html
    "vue/custom-event-name-casing": ["off"],

    // https://eslint.vuejs.org/rules/component-name-in-template-casing.html
    "vue/component-name-in-template-casing": [
      "error",
      "PascalCase",
      {
        registeredComponentsOnly: false,
        ignores: ["router-view", "router-link", "teleport", "component", "transition", "transition-group", "slot", "keep-alive"],
      },
    ],
  },
}
