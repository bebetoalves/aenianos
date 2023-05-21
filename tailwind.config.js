const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                success: colors.green,
                warning: colors.yellow,
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
    safelist: [
        {pattern: /(bg|text|ring)-(lime|green|violet|pink|amber|red|cyan|emerald|slate|orange|purple|sky)-(50|500|600)/},
        {
            pattern: /grid-(cols)-([123])/,
            variants: ['lg'],
        },
    ],
}
