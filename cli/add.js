const importInScss = require('./importScssFile');
const args = {};

process.argv.slice(2).forEach(element => {
    args[element.split('=')[0]] = element.split('=')[1];
});

importInScss(args.name);
