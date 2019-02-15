'use strict';

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const config = require('./config');
const reload = require('./server/reload');
const pages = require('./pages');
const styles = require('./styles');

/**
 * WATCH
 */
function watch() {
    gulp.watch(config.pages.watch, gulp.series(pages, reload));
    gulp.watch(config.styles.input, gulp.series(styles, reload));
    gulp.watch(config.scripts.output + '**/*.js', gulp.parallel(reload));
}

watch.displayName = 'Watch';
watch.description = 'Watch dev files';

module.exports = watch;
