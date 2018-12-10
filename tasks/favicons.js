'use strict';

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const config = require('./config');
const rename = require('gulp-rename');
const responsive = require('gulp-responsive');
const filter = require('gulp-filter');

const pngFilter = filter('**/*.png', {restore: true});

function favicons() {
    return gulp.src(config.favicons.input)
        .pipe(pngFilter)
        .pipe(responsive({
            '**/*.png': [{}]
        }, {
            progressive: true,
            withMetadata: false,
            errorOnEnlargement: false,
            silent: true,
            skipOnEnlargement: true
        }))
        .pipe(rename(function(path) {
            // для винды вот такой вид path.dirname.split('\\')[0]
            path.dirname = path.dirname.split('/')[0];
        }))
        .pipe(pngFilter.restore)
        .pipe(gulp.dest(config.favicons.output))
}

favicons.displayName = 'favicons';
favicons.description = 'Minify favicons';

module.exports = favicons;
