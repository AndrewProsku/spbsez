const fs = require('fs');
const path = require('path');
const dirPath = path.resolve(__dirname, '../../src/pages');

function template (name) {
return `.l-${name} {
\t
}
`;
}

module.exports = function(name) {
    const file = `${dirPath}/${name}.scss`;

    fs.writeFile(file, template(name), err => {
        if (err) throw err;
        console.info('\x1b[36m%s\x1b[0m', 'Create scss. Done!');
    });
};
