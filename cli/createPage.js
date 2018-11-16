const createScss = require('./pageFiles/create-scss');
const createTwig = require('./pageFiles/create-twig');
const importInScss = require('./importScssFile');
const args = {};

process.argv.slice(2).forEach(element => {
    args[element.split('=')[0]] = element.split('=')[1];
});

createScss(args.name);
createTwig(args.name);
importInScss(args.name, true);
