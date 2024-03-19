const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')
import forms from '@tailwindcss/forms';


module.exports = {
  purge: [
    // prettier-ignore
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      blue: colors.blue,
      black: colors.black,
      white: colors.white,
      red: colors.red,
      orange: colors.orange,
      yellow: colors.yellow,
      green: colors.green,
      gray: colors.blueGray,
      indigo: {
        100: '#e6e8ff',
        300: '#b2b7ff',
        400: '#7886d7',
        500: '#6574cd',
        600: '#5661b3',
        800: '#2f365f',
        900: '#191e38',
      },
      // primevue
      'primary-50': 'rgb(var(--primary-50))',
      'primary-100': 'rgb(var(--primary-100))',
      'primary-200': 'rgb(var(--primary-200))',
      'primary-300': 'rgb(var(--primary-300))',
      'primary-400': 'rgb(var(--primary-400))',
      'primary-500': 'rgb(var(--primary-500))',
      'primary-600': 'rgb(var(--primary-600))',
      'primary-700': 'rgb(var(--primary-700))',
      'primary-800': 'rgb(var(--primary-800))',
      'primary-900': 'rgb(var(--primary-900))',
      'primary-950': 'rgb(var(--primary-950))',
      'surface-0': 'rgb(var(--surface-0))',
      'surface-50': 'rgb(var(--surface-50))',
      'surface-100': 'rgb(var(--surface-100))',
      'surface-200': 'rgb(var(--surface-200))',
      'surface-300': 'rgb(var(--surface-300))',
      'surface-400': 'rgb(var(--surface-400))',
      'surface-500': 'rgb(var(--surface-500))',
      'surface-600': 'rgb(var(--surface-600))',
      'surface-700': 'rgb(var(--surface-700))',
      'surface-800': 'rgb(var(--surface-800))',
      'surface-900': 'rgb(var(--surface-900))',
      'surface-950': 'rgb(var(--surface-950))'
      // primevue
    },
    extend: {
      borderColor: theme => ({
        DEFAULT: theme('colors.gray.200', 'currentColor'),
      }),
      fontFamily: {
        sans: ['Cerebri Sans', ...defaultTheme.fontFamily.sans],
      },
      boxShadow: theme => ({
        outline: '0 0 0 2px ' + theme('colors.indigo.500'),
      }),
      fill: theme => theme('colors'),
      colors: {
        ...colors
      }
    },
  },
  variants: {
    extend: {
      fill: ['focus', 'group-hover'],
    },
  },
  plugins: [
    forms,
    require('flowbite/plugin'),
  ],
  content: [
    "./node_modules/flowbite/**/*.js",   
    './resources/**/*.vue', 
  ],
  safelist: [
    //'text-yellow-500',
    {
      pattern: /(text|bg)-(yellow|blue|purple|red)-(200|500)/,
    },        
  ],
}
