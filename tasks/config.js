const sourceBase = 'src';
const buildBase = 'www';

module.exports = {
    server: {
        watch: `${buildBase}`
    },
    scripts: {
        input: `${sourceBase}/**/*.js`,
        output: `${buildBase}/scripts`
    },
    styles: {
        base: `${sourceBase}/common/styles/app.scss`,
        input: `${sourceBase}/**/*.scss`,
        output: `${buildBase}/styles`
    },
    pages: {
        input: `${sourceBase}/pages/**/*.twig`,
        watch: [`${sourceBase}/pages/**/*.twig`, `${sourceBase}/components/**/*.twig`],
        output: `${buildBase}/pages`
    },
    fonts: {
        input: `${sourceBase}/fonts/**/*.{ttf,otf}`,
        output: `${buildBase}/fonts`
    },
    favicons: {
        input: `${sourceBase}/favicons/*.{ico,png}`,
        output: `${buildBase}/favicons`
    },
    images: {
        input: `${sourceBase}/components/**/*.{jpg,png,jpeg,webp}`,
        output: `${buildBase}/images`
    },
    svg: {
        input: `${sourceBase}/components/**/*.svg`,
        output: `${buildBase}/icons`
    },
    NODE_ENV : process.env.NODE_ENV || 'development', // or production
    isProduction : this.NODE_ENV === 'production'
};
