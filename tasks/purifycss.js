'use strict';

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const purify = require('gulp-purifycss');
const config = require('./config');

function purifycss() {
    return gulp.src(`${config.styles.output}/**/*.css`)
        .pipe(purify([`${config.pages.output}/*.html`, `${config.scripts.output}/*.js`]))
        .pipe(gulp.dest(config.styles.output));
}

purifycss.displayName = 'PurifyCSS';
purifycss.description = 'A function that takes content (HTML/JS/PHP/etc) and CSS, and returns only the used CSS.';

module.exports = purifycss;
