const fs = require('fs');
const path = require('path');
const gutil = require('gulp-util');
const dirPath = path.resolve(__dirname, '../src/pages');
const importFile = path.resolve(__dirname, '../src/common/styles/app.scss');
const args = {};

process.argv.slice(2).forEach(element => {
    args[element.split('=')[0]] = element.split('=')[1];
});

if (!args.name) {
    console.error('\x1b[31m%s\x1b[0m', 'Удалять нечего. Укажите страницу - npm run removePage name="имяСтраницы"');
    return false;
}

function deletePageFiles(path) {
    let pathFiles = [path + '.twig', path + '.scss'];
    pathFiles.forEach(function(file) {
        fs.exists(file, function(exists) {
            if(exists) {
                console.log(gutil.colors.green('Файл ' + file + ' удален'));
                fs.unlinkSync(file);
            } else {
                console.log(gutil.colors.red('Файл ' + file + ' не найден'));
            }
        })
    });
}

function deleteImportScss(file) {
    fs.readFile(importFile, 'utf8', (error, data) => {
        if (error) {
            return console.log(error);
        }
        let reg = '@import "../../pages/' + file + '";';
        let result = data.split(reg);
        result = result[0].trim() + result[1];
        fs.writeFile(importFile, result, 'utf8', function (err) {
            if (err) return console.log(err);
        });
    });
}

deletePageFiles(`${dirPath}/${args.name}`);
deleteImportScss(`${args.name}`);
