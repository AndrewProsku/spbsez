'use strict';

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const font = require('./tasks/font');
const favicons = require('./tasks/favicons');
const images = require('./tasks/images');
const svg = require('./tasks/svg');
const pages = require('./tasks/pages');
const clean = require('./tasks/clean');
const purifycss = require('./tasks/purifycss');
const server = require('./tasks/server/server').server;
const watch = require('./tasks/watch');

/**
 * TASKS
 */
gulp.task('favicons', gulp.series(favicons));

gulp.task('icons', gulp.series(svg));

gulp.task('images', gulp.series(images));

gulp.task('fonts', gulp.series(font));

gulp.task('build', gulp.series(clean, gulp.parallel(font, images, pages, purifycss, svg)));

gulp.task('dev', gulp.series(server, watch));

gulp.task('production', gulp.series(clean, 'build'));
