/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/Filament/**/*.php',
    './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0B2B2B',
          hover: '#0F3D3D',
          light: '#1A4A4A',
        },
        whatsapp: '#25D366',
        sale: '#C0392B',
        offwhite: '#F9F9F9',
        'text-dark': '#1A1A1A',
        'text-medium': '#4A4A4A',
        'text-light': '#888888',
        border: '#E8E8E8',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        heading: ['Outfit', 'Inter', 'sans-serif'],
      },
      aspectRatio: {
        '4/5': '4 / 5',
      },
    },
  },
  plugins: [],
}