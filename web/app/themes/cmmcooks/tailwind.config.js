const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    important: true,
    content: [
        './*.php',
        './template-parts/*.php',
        './template-parts/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],
};
