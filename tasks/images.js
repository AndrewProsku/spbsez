'use strict';

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const config = require('./config');
const rename        = require('gulp-rename');
const responsive    = require('gulp-responsive');

function images() {
    return gulp.src(config.images.input)
        .pipe(responsive({
            '**/*': [
                {}, {
                    width: 320,
                    rename: {
                        suffix: '-320'
                    }
                }, {
                    width: 670,
                    rename: {
                        suffix: '-670'
                    }
                }, {
                    width: 960,
                    rename: {
                        suffix: '-960'
                    }
                }, {
                    width: 1280,
                    rename: {
                        suffix: '-1280'
                    }
                }, {
                    width: 1920,
                    rename: {
                        suffix: '-1920'
                    }
                }, {
                    width: 2560,
                    rename: {
                        suffix: '-2560'
                    }
                }]
        }, {
            quality: 80,
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
        .pipe(gulp.dest(config.images.output))
}

images.displayName = 'images';
images.description = 'Minify images';

module.exports = images;