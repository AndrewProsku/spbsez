const fs = require('fs');
const path = require('path');
const file = path.resolve(__dirname, '../src/common/styles/app.scss');

module.exports = function(name, layout) {
    let append = null;

    if (layout) {
        append = `@import "../../pages/${name}";\n`;
    } else {
        append = `@import "../../components/${name}/${name}";\n`;
    }

    fs.appendFile(file, append, function(err) {
        if (err) throw err;

        console.info('\x1b[36m%s\x1b[0m', 'Стили компонента подключены!');
    });
};

