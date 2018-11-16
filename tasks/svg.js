'use strict';

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const config = require('./config');
const svgo = require('gulp-svgo');

function svg() {
    return gulp.src(config.svg.input)
        .pipe(svgo({
            plugins: [{
                removeStyleElement: true
            }]
        }))
        .pipe(gulp.dest(config.svg.output));
}

svg.displayName = 'Svg sprite';
svg.description = 'Create svg sprite';

module.exports = svg;
