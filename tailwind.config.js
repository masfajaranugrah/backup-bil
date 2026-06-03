/** @type {import('tailwindcss').Config} */
export default {
  prefix: 'tw-',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue'
  ],
  corePlugins: {
    preflight: false
  },
  theme: {
    extend: {
      boxShadow: {
        soft: '0 10px 30px rgba(2, 6, 23, 0.08)'
      }
    }
  },
  plugins: []
};
