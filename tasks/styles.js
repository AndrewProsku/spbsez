/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const config = require('./config');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const cssnano = require('gulp-cssnano');
const isProd = require('./utils').isProd;
const gulpif = require('gulp-if');

/**
 * PAGES
 * @returns {*}
 */
function styles() {
    return gulp.src(config.styles.base)
        .pipe(gulpif(isProd, sourcemaps.init()))
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(gulpif(isProd, cssnano()))
        .pipe(gulpif(isProd, sourcemaps.write('sourcemaps')))
        .pipe(gulp.dest(config.styles.output));
}

styles.displayName = 'Styles';
styles.description = 'Convert scss on css';

module.exports = styles;
