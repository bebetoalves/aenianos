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
        require('@tailwindcss/line-clamp')
    ],
    safelist: [
        {pattern: /bg-(lime|green|violet|pink|amber|red|cyan|emerald|slate|orange|purple)-100/},
        {pattern: /text-(lime|green|violet|pink|amber|red|cyan|emerald|slate|orange|purple)-800/},
        {
            pattern: /grid-(cols)-([123])/,
            variants: ['lg'],
        },
    ],
}
