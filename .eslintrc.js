module.exports = {
  root: true,
  extends: ['plugin:tailwindcss/recommended'],
  parserOptions: {
    sourceType: 'module',
    ecmaVersion: 'latest'
  },
  overrides: [
    {
      files: ['*.blade.php'],
      parser: '@angular-eslint/template-parser',
    },
  ],
  settings: {
    tailwindcss: {
      config: 'tailwind.config.js',
      cssFiles: ['resources/css/app.css'],
    },
  },
};