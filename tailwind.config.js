const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme');

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
            },
            fontFamily: {
                primary: ['Inter', ...defaultTheme.fontFamily.sans],
                secondary: ['Space Grotesk', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/line-clamp')
    ],
    safelist: [
        {
            pattern: /bg-(lime|green|violet|pink|amber|red|cyan|emerald|slate|orange)-100/
        },
        {
            pattern: /text-(lime|green|violet|pink|amber|red|cyan|emerald|slate|orange)-800/
        },
        {
            pattern: /grid-(cols)-([123])/,
            variants: ['lg'],
        },
    ],
}
