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
    console.error('\x1b[31m%s\x1b[0m', 'Переименовывать нечего. Укажите компонент - npm run renameComponent name="имяКомпонента" newName="новоеИмя"');
    return;
}

if (!args.newName) {
    console.error('\x1b[31m%s\x1b[0m', 'Не указано новое имя. Укажите новое имя компонента - npm run renameComponent name="имяКомпонента" newName="новоеИмя"');
    return;
}

function renamePageFiles(name, newName) {
    const path = `${dirPath}/${name}`;
    const pathNew = `${dirPath}/${newName}`;
    const dataNamesFiles = [
        {
            name: path + '.twig',
            newName: pathNew + '.twig'
        },
        {
            name: path + '.scss',
            newName: pathNew + '.scss'
        },
    ];
    dataNamesFiles.forEach(function(file) {
        fs.exists(file.name, function(exists) {
            if(exists) {
                fs.rename(file.name, file.newName, (err) => {
                    if (err) return console.error(err);
                    console.info(gutil.colors.green(`Файл ${file.name} переименован`));
                });
            } else {
                console.error(gutil.colors.red(`Файл ${file.name} не найден`));
            }
        })
    });
}

function renameImportScss(name, newName) {
    fs.readFile(importFile, 'utf8', (error, data) => {
        const reg = `@import "../../pages/${name}";`;
        const newReg = `@import "../../pages/${newName}";`;

        if (error) {
            return console.error(error);
        }

        if (data.search(reg) === -1) {
            return console.error(gutil.colors.red(`Строка ${reg} в ${importFile} не найдена`));
        }
        const result = data.replace(reg, newReg);
        fs.writeFile(importFile, result, 'utf8', function (err) {
            if (err) return console.error(err);
            console.info(gutil.colors.green(`Строка ${reg} в ${importFile} переименована`));
        });
    });
}

renamePageFiles(args.name, args.newName);
renameImportScss(args.name, args.newName);
