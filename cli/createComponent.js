const fs = require('fs');
const path = require('path');
const dirPath = path.resolve(__dirname, '../src/components');
const createScss = require('./createFiles/create-scss');
const createTwig = require('./createFiles/create-twig');
const createJs = require('./createFiles/create-js');
const importInScss = require('./importScssFile');
const args = {};

process.argv.slice(2).forEach(element => {
    args[element.split('=')[0]] = element.split('=')[1];
});

function createFolder(dirName) {
    const folder = `${dirPath}/${dirName}`;

    fs.mkdir(folder, errorDir => {
        if (!errorDir) {
            createScss(args.name);
            createTwig(args.name);
            createJs(args.name);
            importInScss(args.name);

            console.info('\x1b[36m%s\x1b[0m', 'Компонент создан');
        } else {
            console.error('\x1b[31m%s\x1b[0m', 'Такой компонент уже существует');
        }
    });
}

createFolder(args.name);
