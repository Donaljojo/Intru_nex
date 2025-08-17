// tailwind.config.js
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        intrunex: {
          dark: '#0a192f',    // deep tech navy
          accent: '#00f5d4',  // neon cyan
          accent2: '#a3ff12', // lime punch
        }
      }
    }
  },
  plugins: [
  require('flowbite/plugin')
]

}
