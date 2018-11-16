const posthtml = require('posthtml');
const fs = require('fs');
const path = require('path');
const dirPath = path.resolve(__dirname, '../src/pages');
const args = {};
const key = 0;
const value = 1;
const trashItem = 2;
let modulePath = '';

/**
 * Набивает args переданными в консоле агрументами
 * из npm run generateCss name="component" получается args={name: 'component'}
 */
process.argv.slice(trashItem).forEach(element => {
    args[element.split('=')[key]] = element.split('=')[value];
});

modulePath = `${dirPath}/${args.name}.twig`;

if (fs.existsSync(modulePath)) {
    const html = fs.readFileSync(modulePath).toString();

    const config = {
        fileSave  : true,
        filePath  : path.resolve(__dirname, `../src/pages/${args.name}.scss`),
        overwrite : true,
        eol       : '\n',
        nested    : false,
        curlbraces: true,
        elemPrefix: '__',
        modPrefix : '_',
        modDlmtr  : '_'
    };

    posthtml().use(require('posthtml-classes')(config)).process(html);
} else {
    console.error('\x1b[31m%s\x1b[0m', `${args.name} - такой страницы не существует`);
}


