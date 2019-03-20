'use strict';

/**
 * DEPENDENCIES
 */
const gulp = require('gulp');
const config = require('./config');
const fontgen = require('gulp-fontgen');

/**
 * FONTS
 * @type {string}
 */
const text = `
АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ
абвгдеёжзийклмнопрстуфхцчшщъыьэюя
ABCDEFGHIJKLMNOPQRSTUVWXYZ
abcdefghijklmnopqrstuvwxyz
0123456789
!@#$%^&*()_+-={}[]|":;'><?,./«»№₽`;

function fonts() {
    return gulp.src(config.fonts.input)
        .pipe(fontgen({
            subset : text,
            dest   : config.fonts.output,
            collate: true
        }));
}

fonts.displayName = 'Fonts';
fonts.description = 'Minify fonts';

module.exports = fonts;
