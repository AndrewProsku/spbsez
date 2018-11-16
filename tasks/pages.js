

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const config = require('./config');
const twig = require('gulp-twig');
const plumber = require('gulp-plumber');
const typograf = require('gulp-typograf');

/**
 * PAGES
 * @returns {*}
 */
function pages() {
    return gulp.src(config.pages.input)
        .pipe(plumber({
            errorHandler(err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(twig())
        .pipe(typograf({
            locale     : ['ru'],
            disableRule: ['ru/nbsp/centuries', 'common/number/fraction']
        }))
        .pipe(gulp.dest(config.pages.output));
}

pages.displayName = 'Pages';
pages.description = 'Convert twig on html';

module.exports = pages;
