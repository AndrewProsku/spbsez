const fs = require('fs');
const path = require('path');
const dirPath = path.resolve(__dirname, '../src/components');
const args = {};

process.argv.slice(2).forEach(element => {
    args[element.split('=')[0]] = element.split('=')[1];
});

if (!args.name) {
    console.error('\x1b[31m%s\x1b[0m', 'Удалять нечего. Укажите имя компонента - npm run removeComponen name="имяКомпонента"');
    return false;
}

function deleteFolderRecursive(path) {
    if (fs.existsSync(path)) {
        fs.readdirSync(path).forEach(function(file){
            var curPath = path + "/" + file;
            if (fs.lstatSync(curPath).isDirectory()) {
                deleteFolderRecursive(curPath);
            } else {
                fs.unlinkSync(curPath);
            }
        });
        fs.rmdirSync(path);
    }
}

deleteFolderRecursive(`${dirPath}/${args.name}`);
